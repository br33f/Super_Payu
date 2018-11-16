<?php
/**
 * User: damian
 * Date: 14.11.18
 * Time: 11:34
 */

class Super_Payments_Helper_Payment extends Mage_Core_Helper_Abstract
{
    /**
     * @param int $orderId - order id magento
     * @param string $key
     * @return bool
     */
    public function isPermitted($orderId, $key)
    {
        $order = Mage::getModel('sales/order')->load($orderId);
        if (empty($order->getId()))
            return false;

        return $key == $this->generateKey($orderId);
    }

    /**
     * @param string $orderId
     * @return string
     */
    public function generateKey($orderId)
    {
        return hash('sha256', Mage::helper('super_payments/config')->getPassword() . $orderId);
    }

    /**
     * @param int $orderId
     * @return string
     */
    public function generatePaymentContinueUrl($orderId)
    {
        return $this->generateUrlWithOrderKey('superpayments/payment/continue', $orderId);
    }

    /**
     * @param int $orderId
     * @return string
     */
    public function generateStatusCheckUrl($orderId)
    {
        return $this->generateUrlWithOrderKey('superpayments/payment/statusCheck', $orderId);
    }

    /**
     * @param string $uri
     * @param int $orderId
     * @return string
     */
    public function generateUrlWithOrderKey($uri, $orderId)
    {
        $order = Mage::getModel('sales/order')->load($orderId);

        $params = array(
            'orderId' => $orderId,
            '_type' => Mage_Core_Model_Store::URL_TYPE_WEB,
            '_store' => $order->getStoreId(),
            'key' => $this->generateKey($orderId)
        );

        return Mage::getUrl($uri, $params);
    }
}