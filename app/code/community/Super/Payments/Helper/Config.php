<?php
/**
 * User: damian
 * Date: 31.10.18
 * Time: 10:50
 */

class Super_Payments_Helper_Config extends Mage_Core_Helper_Abstract
{
    /**
     * @return string
     */
    public function getPassword()
    {
        return Mage::getStoreConfig('payment/super_payments/password');
    }

}