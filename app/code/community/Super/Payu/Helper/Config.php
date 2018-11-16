<?php
/**
 * User: damian
 * Date: 31.10.18
 * Time: 10:50
 */

class Super_Payu_Helper_Config extends Mage_Core_Helper_Abstract
{
    /**
     * @return string
     */
    public function getPaymentTitle()
    {
        return Mage::getStoreConfig('payment/super_payu/title');
    }

    /**
     * @return string
     */
    public function getPaymentInstallmentsTitle()
    {
        return Mage::getStoreConfig('payment/super_payu_installments/title');
    }

    /**
     * @return int
     */
    public function getPaymentSortOrder()
    {
        return Mage::getStoreConfig('payment/super_payu/sort_order');
    }

    /**
     * @return bool
     */
    public function isSandbox()
    {
        return Mage::getStoreConfig('payment/super_payu/sandbox');
    }

    /**
     * @return string
     */
    public function getPosId()
    {
        return Mage::getStoreConfig('payment/super_payu/pos_id');
    }

    /**
     * @return string
     */
    public function getMd5Key2()
    {
        return Mage::getStoreConfig('payment/super_payu/md5key2');
    }

    /**
     * @return string
     */
    public function getOauthId()
    {
        return Mage::getStoreConfig('payment/super_payu/oauth_id');
    }

    /**
     * @return string
     */
    public function getOauthSecret()
    {
        return Mage::getStoreConfig('payment/super_payu/oauth_secret');
    }

}