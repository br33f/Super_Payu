<?php
/**
 * User: damian
 * Date: 06.11.18
 * Time: 11:08
 */

class Super_Payments_Block_Transaction_List extends Super_Payments_Block_Transaction_Abstract
{
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->__('Lista transakcji');
    }

    /**
     * @param Mage_Sales_Model_Order_Payment_Transaction $transaction
     * @return string
     */
    public function getTransactionStatusLabel($transaction)
    {
        $label = "";

        if (!$transaction->getIsClosed()) {
            $label = $this->__('W trakcie');
        } else {
            if ($transaction->getTxnId() == $this->getOrder()->getPayment()->getLastTransId() && $this->isOrderPaid())
                $label = $this->__('Zakończona');
            else
                $label = $this->__('Anulowana');
        }

        return $label;
    }
}