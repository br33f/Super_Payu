<?php
/**
 * User: damian
 * Date: 13.11.18
 * Time: 11:01
 */

class Super_Payments_Model_Payment
{
    /**
     * @var Mage_Sales_Model_Order|null $order
     */
    private $order = null;

    /**
     * @return Mage_Sales_Model_Order|null
     */
    private function getOrder($order = null)
    {
        return !empty($order) ? $order : $this->order;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * Method returns list of all transactions associated with order
     * @param Mage_Sales_Model_Order $order
     * @return Mage_Sales_Model_Resource_Order_Payment_Transaction_Collection
     */
    public function getTransactions($order = null)
    {
        $transactions = Mage::getModel('sales/order_payment_transaction')->getCollection()
            ->addAttributeToFilter('order_id', array('eq' => $this->getOrder($order)->getEntityId()))
            ->addAttributeToFilter('txn_type', array('eq' => 'capture'))
            ->addOrder('created_at', Varien_Data_Collection::SORT_ORDER_DESC);

        return $transactions;
    }

    /**
     * Method returns true if order is already paid
     * @param Mage_Sales_Model_Order $order
     * @return bool
     */
    public function isOrderPaid($order = null)
    {
        return $this->getOrder($order)->getBaseTotalDue() == 0;
    }

    /**
     * Method returns true if order has open transaction
     * @param Mage_Sales_Model_Order $order
     * @return bool
     */
    public function isPaymentInProgress($order = null)
    {
        $isPaymentInProgress = false;

        /** @var Mage_Sales_Model_Order_Payment_Transaction[] $transactions */
        $transactions = $this->getTransactions($order);
        foreach ($transactions as $transaction) {
            $isPaymentInProgress |= !$transaction->getIsClosed();
        }

        return $isPaymentInProgress;
    }

    /**
     * @param Mage_Sales_Model_Order_Payment $payment
     * @param string $transactionId
     */
    public function addPaymentTransaction($payment, $transactionId)
    {
        $comment = Mage::helper('super_payments')->__('Rozpoczęto nową transakcję.');

        $payment->setTransactionId($transactionId)
            ->setPreparedMessage($comment)
            ->setCurrencyCode($payment->getOrder()->getBaseCurrencyCode())
            ->setIsTransactionApproved(false)
            ->setIsTransactionClosed(false)
            ->save();
        $payment->addTransaction(Mage_Sales_Model_Order_Payment_Transaction::TYPE_CAPTURE, null, false, $comment)
            ->save();
        $payment->getOrder()
            ->save();
    }
}