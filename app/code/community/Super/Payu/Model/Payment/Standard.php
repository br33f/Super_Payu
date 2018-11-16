<?php
/**
 * User: damian
 * Date: 13.11.18
 * Time: 13:34
 */

class Super_Payu_Model_Payment_Standard extends Super_Payu_Model_Payment_Abstract
{
    const EVENT_PREFIX = "super_payu";
    protected $_code = 'super_payu';

    protected $_formBlockType = 'super_payu/form';
    protected $_infoBlockType = 'super_payu/info';

    protected function getOrderRestModel()
    {
        return Mage::getModel('super_payu/rest_order');
    }

    protected function getEventPrefix()
    {
        return self::EVENT_PREFIX;
    }

    protected function getPaymethodsHelper()
    {
        return Mage::helper('super_payu/paymethods');
    }
}