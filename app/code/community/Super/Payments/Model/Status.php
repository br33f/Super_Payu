<?php
/**
 * User: damian
 * Date: 06.11.18
 * Time: 10:23
 */

class Super_Payments_Model_Status
{
    /**
     * Change the status to canceled
     *
     * @param Mage_Sales_Model_Order_Payment $payment
     */
    public function updatePaymentStatusCanceled($payment)
    {
        $comment = Mage::helper('super_payments')->__('Transakcja została anulowana. ID transakcji: "%s"', $payment->getLastTransId());

        $payment
            ->setPreparedMessage($comment)
            ->setIsTransactionApproved(true)
            ->setIsTransactionClosed(true)
            ->save();

        $payment->getTransaction($payment->getLastTransId())->setIsClosed(true)->save();

        $payment->getOrder()
            ->addStatusHistoryComment($comment)
            ->setIsCustomerNotified(true)
            ->save();
    }

    /**
     * Change the status to canceled
     *
     * @param Mage_Sales_Model_Order_Payment $payment
     */
    public function cancelPaymentByUser($payment)
    {
        $comment = Mage::helper('super_payments')->__('Transakcja została anulowana przez użytkownika. ID transakcji: "%s"', $payment->getLastTransId());

        $payment
            ->setPreparedMessage($comment)
            ->setIsTransactionApproved(true)
            ->setIsTransactionClosed(true)
            ->save();

        $payment->getTransaction($payment->getLastTransId())->setIsClosed(true)->save();

        $payment->getOrder()
            ->addStatusHistoryComment($comment)
            ->setIsCustomerNotified(false)
            ->save();
    }

    /**
     * Change the status to rejected
     *
     * @param Mage_Sales_Model_Order_Payment $payment
     */
    public function updatePaymentStatusRejected($payment)
    {
        $comment = Mage::helper('super_payments')->__('Transakcja została odrzucona. ID transakcji: "%s"', $payment->getLastTransId());

        $payment
            ->setPreparedMessage($comment)
            ->setIsTransactionApproved(true)
            ->setIsTransactionClosed(true)
            ->save();

        $payment->getTransaction($payment->getLastTransId())->setIsClosed(true)->save();

        $payment->getOrder()
            ->addStatusHistoryComment($comment)
            ->setIsCustomerNotified(true)
            ->save();
    }

    /**
     * Update payment status to complete
     *
     * @param Mage_Sales_Model_Order_Payment $payment
     */
    public function updatePaymentStatusCompleted($payment)
    {
        $comment = Mage::helper('super_payments')->__('Dziękujemy za opłacenie zamówienia. Twoje zamówienie zostanie przyjęte do realizacji maksymalnie w ciągu jednego dnia roboczego, o czym zostaniesz poinformowany w osobnej wiadomości mailowej.');

        $payment
            ->setPreparedMessage($comment)
            ->setCurrencyCode($payment->getOrder()->getBaseCurrencyCode())
            ->setIsTransactionApproved(true)
            ->setIsTransactionClosed(true)
            ->registerCaptureNotification($payment->getOrder()->getTotalDue(), true)
            ->save();

        $payment->getOrder()
            ->setState(Mage_Sales_Model_Order::STATE_NEW, 'paid', $comment, true)
            ->save();
    }
}