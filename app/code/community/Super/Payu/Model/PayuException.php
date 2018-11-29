<?php
/**
 * User: damian
 * Date: 29.11.18
 * Time: 10:19
 */

class Super_Payu_Model_PayuException extends Mage_Core_Exception
{
    private $redirectUrl;

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl ? $this->redirectUrl : Mage::getUrl('customer/account/');
    }

    /**
     * @param $redirectUrl
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

}
