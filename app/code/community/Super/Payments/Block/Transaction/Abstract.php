<?php
/**
 * User: damian
 * Date: 31.10.18
 * Time: 13:55
 */

abstract class Super_Payments_Block_Transaction_Abstract extends Mage_Core_Block_Template
{
    /**
     * @return Mage_Sales_Model_Order|null
     */
    public function getOrder()
    {
        $orderId = Mage::registry('order_id');
        return Mage::getModel('sales/order')->load($orderId);
    }

    /**
     * @return Mage_Sales_Model_Resource_Order_Payment_Transaction_Collection
     */
    public function getTransactions()
    {
        return $this->getPaymentModelInstance()->getTransactions();
    }

    /**
     * @return bool
     */
    public function isOrderPaid()
    {
        return $this->getPaymentModelInstance()->isOrderPaid();
    }

    /**
     * @return bool
     */
    public function isPaymentInProgress()
    {
        return $this->getPaymentModelInstance()->isPaymentInProgress();
    }

    /**
     * @return Super_Payments_Model_Payment
     */
    protected function getPaymentModelInstance()
    {
        $paymentModel = Mage::getModel('super_payments/payment');
        $paymentModel->setOrder($this->getOrder());

        return $paymentModel;
    }

    /**
     * Method returns true is orders state is holded/canceled
     *
     * @return bool
     */
    public function isOrderCanceledOrHolded()
    {
        $isClosed = false;

        $isClosed |= $this->getOrder()->getState() == Mage_Sales_Model_Order::STATE_CANCELED;
        $isClosed |= $this->getOrder()->getState() == Mage_Sales_Model_Order::STATE_HOLDED;

        return $isClosed;
    }
}