<?php
/**
 * User: damian
 * Date: 31.10.18
 * Time: 11:00
 */

class Super_Payu_Block_Info extends Mage_Payment_Block_Info
{
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate(Super_Payu_Helper_Data::DESIGN_CATALOG . 'payment/info.phtml');
    }

    /**
     * @return string
     */
    public function getTransactionUrl()
    {
        return Mage::helper('super_payments/transaction')->generateListUrl($this->getOrder()->getId());
    }

    /**
     * Retrieve current order model instance
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return $this->getInfo()->getOrder();
    }
}