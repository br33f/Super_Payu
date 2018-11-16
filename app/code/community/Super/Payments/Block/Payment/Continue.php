<?php
/**
 * User: damian
 * Date: 07.11.18
 * Time: 10:27
 */

class Super_Payments_Block_Payment_Continue extends Mage_Core_Block_Template
{
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->__('Status płatności zamówienia #%s', $this->getOrder()->getRealOrderId());
    }

    /**
     * @return Mage_Sales_Model_Order|null
     */
    public function getOrder()
    {
        $orderId = Mage::registry('order_id');
        return Mage::getModel('sales/order')->load($orderId);
    }

    /**
     * @return string
     */
    public function getPaymentStatusCheckUrl()
    {
        return Mage::helper('super_payments/payment')->generateStatusCheckUrl($this->getOrder()->getId());
    }

}