<?php
/**
 * Created by PhpStorm.
 * User: damian
 * Date: 05.11.18
 * Time: 14:19
 */

class Super_Payments_PaymentController extends Mage_Core_Controller_Front_Action
{
    const PAYMENT_STATUS_WAITING = 'payment-waiting';
    const PAYMENT_STATUS_SUCCESS = 'payment-success';
    const PAYMENT_STATUS_FAIL = 'payment-fail';

    public function continueAction()
    {
        $orderId = $this->getRequest()->getParam('orderId');
        $key = $this->getRequest()->getParam('key');

        if (Mage::helper('super_payments/transaction')->isPermitted($orderId, $key)) {
            Mage::register('order_id', $orderId);
            $this->_title($this->__('Status płatnośći zamówienia'));
            $this->loadLayout();
            $this->renderLayout();
        } else {
            Mage::getSingleton('core/session')->addError($this->__('Brak dostępu do transakcji zamówienia o id: %d', $orderId));
            $this->_redirect('customer/account/');
        }
    }

    public function statusCheckAction()
    {
        $orderId = $this->getRequest()->getParam('orderId');
        $order = Mage::getModel('sales/order')->load($orderId);
        $key = $this->getRequest()->getParam('key');

        if (Mage::helper('super_payments/transaction')->isPermitted($orderId, $key)) {
            $paymentModel = Mage::getModel('super_payments/payment');
            $paymentModel->setOrder($order);

            if ($paymentModel->isOrderPaid()) {
                echo self::PAYMENT_STATUS_SUCCESS;
            } else {
                if ($paymentModel->isPaymentInProgress())
                    echo self::PAYMENT_STATUS_WAITING;
                else
                    echo self::PAYMENT_STATUS_FAIL;
            }
        }
    }
}