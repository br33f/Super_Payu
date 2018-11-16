<?php
/**
 * Created by PhpStorm.
 * User: damian
 * Date: 31.10.18
 * Time: 12:13
 */

class Super_Payments_TransactionController extends Mage_Core_Controller_Front_Action
{
    public function viewAction()
    {
        $orderId = $this->getRequest()->getParam('orderId');
        $key = $this->getRequest()->getParam('key');

        if (Mage::helper('super_payments/transaction')->isPermitted($orderId, $key)) {
            Mage::register('order_id', $orderId);
            $this->_title($this->__('Płatności'));
            $this->loadLayout();
            $this->renderLayout();
        } else {
            Mage::getSingleton('core/session')->addError($this->__('Brak dostępu do transakcji zamówienia o id: %d', $orderId));
            $this->_redirect('customer/account/');
        }
    }
}