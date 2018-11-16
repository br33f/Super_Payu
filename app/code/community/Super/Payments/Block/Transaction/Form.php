<?php
/**
 * User: damian
 * Date: 31.10.18
 * Time: 13:54
 */

class Super_Payments_Block_Transaction_Form extends Super_Payments_Block_Transaction_Abstract
{
    const PENDING_CODE = 'pending';
    const IN_PROGRESS_CODE = 'inprogress';
    const PAID_CODE = 'paid';

    /**
     * @return string
     */
    public function getPaymentRedirectionUrl()
    {
        return Mage::helper('super_payments/transaction')->generateTransactionStartUrl($this->getOrder()->getId());
    }

    /**
     * @return string
     */
    public function getPaymentCancelUrl()
    {
        return Mage::helper('super_payments/transaction')->generateTransactionCancelUrl($this->getOrder()->getId());
    }

    /**
     * @return string
     */
    public function getPaymentStatus()
    {
        $code = self::PENDING_CODE;

        if ($this->isOrderPaid())
            $code = self::PAID_CODE;
        else if ($this->isPaymentInProgress())
            $code = self::IN_PROGRESS_CODE;

        return $code;
    }
}