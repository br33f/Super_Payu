<?php
/**
 * User: damian
 * Date: 31.10.18
 * Time: 12:15
 */

class Super_Payments_Helper_Transaction extends Mage_Core_Helper_Abstract
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

        if ($order->getCustomerIsGuest()) {
            return $key == $this->generateKey($orderId);
        }
        else {
            return Mage::getModel('customer/session')->getCustomerId() == $order->getCustomerId();
        }
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
    public function generateListUrl($orderId)
    {
        return $this->generateUrlWithOrderKey('superpayments/transaction/view', $orderId);
    }

    /**
     * @param int $orderId
     * @return string
     */
    public function generateTransactionStartUrl($orderId)
    {
        $order = Mage::getModel('sales/order')->load($orderId);
        $paymentMethodModel = $order->getPayment()->getMethodInstance();

        if ($paymentMethodModel instanceof Super_Payments_Model_PaymentInterface) {
            return $paymentMethodModel->getTransactionStartUrl($orderId);
        } else {
            return null;
        }
    }

    /**
     * @param int $orderId
     * @return string
     */
    public function generateTransactionCancelUrl($orderId)
    {
        $order = Mage::getModel('sales/order')->load($orderId);
        $paymentMethodModel = $order->getPayment()->getMethodInstance();

        if ($paymentMethodModel instanceof Super_Payments_Model_PaymentInterface) {
            return $paymentMethodModel->getTransactionCancelUrl($orderId);
        } else {
            return null;
        }
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
            '_store' => $order->getStoreId()
        );

        if ($order->getCustomerIsGuest()) {
            $params['key'] = $this->generateKey($orderId);
        }

        return Mage::getUrl($uri, $params);
    }

    /**
     * @param int $orderId
     * @return string
     */
    public function generateOrderViewUrl($orderId)
    {
        $order = Mage::getModel('sales/order')->load($orderId);

        if ($order->getCustomerIsGuest()) {
            $params = array(
                '_store' => $order->getStoreId()
            );

            return Mage::getUrl('sales/guest/view', $params) . '?oar_type=email&oar_order_id=' . $order->getIncrementId() . '&oar_email=' . $order->getCustomerEmail();
        } else {
            $params = array(
                'order_id' => $orderId,
                '_store' => $order->getStoreId()
            );

            return Mage::getUrl('sales/order/view', $params);
        }
    }
}