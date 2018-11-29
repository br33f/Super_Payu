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

        try {
            if (!Mage::helper('super_payments/transaction')->isPermitted($orderId, $key))
                throw new Super_Payu_Model_PayuException($this->__('Brak dostępu do płatności zamówienia o id: %d', $orderId));

            $order = Mage::getModel('sales/order')->load($orderId);
            $result = $this->getPaymentModel()->startPayment($order);

            if ($result->getStatus() == 'SUCCESS') {
                $this->_redirectUrl($result->getResponse()->redirectUri);
            } else {
                throw new Exception();
            }
        } catch (Super_Payu_Model_PayuException $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $this->_redirectUrl($e->getRedirectUrl());
        }
        catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($this->__('Wystąpił nieoczekiwany błąd. Spróbuj ponownie lub skontaktuj się z administratorem.'));
            $this->_redirect('customer/account/');
        }
    }

    public function cancelAction()
    {
        $orderId = $this->getRequest()->getParam('orderId');
        $key = $this->getRequest()->getParam('key');

        try {
            if (!Mage::helper('super_payments/transaction')->isPermitted($orderId, $key))
                throw new Super_Payu_Model_PayuException($this->__('Brak dostępu do płatności zamówienia o id: %d', $orderId));

            $order = Mage::getModel('sales/order')->load($orderId);
            $result = $this->getPaymentModel()->cancelPayment($order);

            if ($result->getStatus() == 'SUCCESS') {
                $this->_redirectReferer();
            } else {
                throw new Exception();
            }
        } catch (Super_Payu_Model_PayuException $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $this->_redirectUrl($e->getRedirectUrl());
        }
        catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($this->__('Wystąpił nieoczekiwany błąd. Spróbuj ponownie lub skontaktuj się z administratorem.'));
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