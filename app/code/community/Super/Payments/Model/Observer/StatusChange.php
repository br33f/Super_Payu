<?php
/**
 * User: damian
 * Date: 06.11.18
 * Time: 10:20
 */

class Super_Payments_Model_Observer_StatusChange
{
    /**
     * @param Varien_Event_Observer $observer
     */
    public function onPaymentCanceled($observer)
    {
        /** @var Mage_Sales_Model_Order_Payment $payment */
        $payment = $observer->getEvent()->getPayment();

        Mage::getModel('super_payments/status')->updatePaymentStatusCanceled($payment);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function onPaymentRejected($observer)
    {
        /** @var Mage_Sales_Model_Order_Payment $payment */
        $payment = $observer->getEvent()->getPayment();

        Mage::getModel('super_payments/status')->updatePaymentStatusRejected($payment);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function onPaymentCompleted($observer)
    {
        /** @var Mage_Sales_Model_Order_Payment $payment */
        $payment = $observer->getEvent()->getPayment();

        Mage::getModel('super_payments/status')->updatePaymentStatusCompleted($payment);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function onPaymentStatusChange($observer)
    {
        /** @var Mage_Sales_Model_Order_Payment $payment */
        $payment = $observer->getEvent()->getPayment();

        /** @var string $status */
        $status = $observer->getEvent()->getStatus();
    }
}