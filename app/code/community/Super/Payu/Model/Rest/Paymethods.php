<?php
/**
 * User: damian
 * Date: 13.11.18
 * Time: 12:48
 */

require_once('vendor/openpayu/openpayu/lib/openpayu.php');

class Super_Payu_Model_Rest_Paymethods
{
    const PAY_METHODS_CACHE_ID = 'super_payu_paymethods';

    /**
     * @var Super_Payu_Model_Rest_Config
     */
    protected $restConfig;

    /**
     * Super_Payu_Model_Rest_Paymethods constructor.
     */
    public function __construct()
    {
        $this->restConfig = Mage::getModel('super_payu/rest_config');
    }

    /**
     * @return array
     * @throws OpenPayU_Exception
     */
    public function getMethods()
    {
        $payMethodsCached = Mage::app()->getCache()->load($this->getPayMethodsCacheId());
        if ($payMethodsCached === false) {
            $this->restConfig->initPayuConfiguration();
            $result = OpenPayU_Retrieve::payMethods();

            if ($result->getStatus() == "SUCCESS") {
                $paymentMethods = $result->getResponse()->payByLinks;
                Mage::app()->getCache()->save(serialize($paymentMethods), $this->getPayMethodsCacheId(), array(), 24 * 3600);
            } else {
                return array();
            }
        } else {
            return unserialize($payMethodsCached);
        }
    }

    protected function getPayMethodsCacheId()
    {
        return self::PAY_METHODS_CACHE_ID;
    }
}