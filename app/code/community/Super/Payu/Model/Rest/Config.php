<?php
/**
 * User: damian
 * Date: 05.11.18
 * Time: 11:10
 */

require_once('vendor/openpayu/openpayu/lib/openpayu.php');

class Super_Payu_Model_Rest_Config
{
    const ENVIRONMENT_SANBOX = 'sandbox';
    const ENVIRONMENT_SECURE = 'secure';

    public function initPayuConfiguration()
    {
        OpenPayU_Configuration::setEnvironment($this->getEnvironment());
        OpenPayU_Configuration::setOauthTokenCache(new OauthCacheFile(Mage::getBaseDir('cache')));

        OpenPayU_Configuration::setMerchantPosId($this->getConfigHelper()->getPosId());
        OpenPayU_Configuration::setSignatureKey($this->getConfigHelper()->getMd5Key2());

        OpenPayU_Configuration::setOauthClientId($this->getConfigHelper()->getOauthId());
        OpenPayU_Configuration::setOauthClientSecret($this->getConfigHelper()->getOauthSecret());
    }

    /**
     * @return string
     */
    protected function getEnvironment() {
        return $this->getConfigHelper()->isSandbox() ? self::ENVIRONMENT_SANBOX : self::ENVIRONMENT_SECURE;
    }

    protected function getConfigHelper() {
        return Mage::helper('super_payu/config');
    }
}