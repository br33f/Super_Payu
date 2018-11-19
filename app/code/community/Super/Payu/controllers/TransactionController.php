<?php
/**
 * Created by PhpStorm.
 * User: damian
 * Date: 31.10.18
 * Time: 12:13
 */

class Super_Payu_TransactionController extends Mage_Core_Controller_Front_Action
{
    public function startAction()
    {
        $orderId = $this->getRequest()->getParam('orderId');
        $key = $this->getRequest()->getParam('key');

        if (Mage::helper('super_payments/transaction')->isPermitted($orderId, $key)) {
            $order = Mage::getModel('sales/order')->load($orderId);
            $result = $this->getPaymentModel()->startPayment($order);

            if ($result->getStatus() == 'SUCCESS') {
                $this->_redirectUrl($result->getResponse()->redirectUri);
            } else {
                Mage::getSingleton('core/session')->addError($this->__('Wystąpił nieoczekiwany błąd. Spróbuj ponownie lub skontaktuj się z administratorem.'));
                $this->_redirect('customer/account/');
            }
        } else {
            Mage::getSingleton('core/session')->addError($this->__('Brak dostępu do płatności zamówienia o id: %d', $orderId));
            $this->_redirect('customer/account/');
        }
    }

    public function cancelAction()
    {
        $orderId = $this->getRequest()->getParam('orderId');
        $key = $this->getRequest()->getParam('key');

        if (Mage::helper('super_payments/transaction')->isPermitted($orderId, $key)) {
            $order = Mage::getModel('sales/order')->load($orderId);
            $result = $this->getPaymentModel()->cancelPayment($order);

            if ($result->getStatus() == 'SUCCESS') {
                $this->_redirectReferer();
            } else {
                Mage::getSingleton('core/session')->addError($this->__('Wystąpił nieoczekiwany błąd. Spróbuj ponownie lub skontaktuj się z administratorem.'));
                $this->_redirectReferer('customer/account/');
            }
        } else {
            Mage::getSingleton('core/session')->addError($this->__('Brak dostępu do płatności zamówienia o id: %d', $orderId));
            $this->_redirect('customer/account/');
        }
    }

    public function notifyAction()
    {
        $this->getPaymentModel()->handleNotifyRequest();
    }

    protected function getPaymentModel()
    {
        return Mage::getModel('super_payu/payment_standard');
    }
}