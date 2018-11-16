<?php
/**
 * User: damian
 * Date: 13.11.18
 * Time: 10:24
 */

interface Super_Payments_Model_PaymentInterface
{
    /**
     * Method generates URL to start a transaction for particular order
     * @param int $orderId
     * @return string
     */
    public function getTransactionStartUrl($orderId);

    /**
     * Method generates URL to cancel a transaction for particular order
     * @param int $orderId
     * @return string
     */
    public function getTransactionCancelUrl($orderId);
}