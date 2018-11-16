<?php
/**
 * User: damian
 * Date: 31.10.18
 * Time: 10:22
 */

abstract class Super_Payu_Model_Payment_Abstract
    extends Mage_Payment_Model_Method_Abstract
    implements Super_Payments_Model_PaymentInterface
{
    protected $_isInitializeNeeded = true;
    protected $_canUseInternal = false;
    protected $_canUseForMultishipping = false;

    /**
     * @param Mage_Sales_Model_Order $order
     * @return OpenPayU_Result
     * @throws OpenPayU_Exception
     */
    public function startPayment($order)
    {
        $result = $this->getOrderRestModel()->create($order);

        Mage::getModel('super_payments/payment')->addPaymentTransaction($order->getPayment(), $result->getResponse()->orderId);

        return $result;
    }

    /**
     * Method triggered by superpayu/transaction/notify method. Handles notification from PayU
     */
    public function handleNotifyRequest()
    {
        try {
            $result = $this->getOrderRestModel()->consumeNotification();

            $response = $result->getResponse();
            $orderRetrieved = $response->order;
            if (isset($orderRetrieved) && is_object($orderRetrieved) && $orderRetrieved->orderId) {
                $transactionId = $orderRetrieved->orderId;
                $status = $orderRetrieved->status;
                $this->updateOrder($transactionId, $status);

                header("HTTP/1.1 200 OK");
            }
        } catch (Exception $e) {
            header('X-PHP-Response-Code: 500', true, 500);
            die($e->getMessage());
        }

        exit;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return OpenPayU_Result
     * @throws OpenPayU_Exception
     */
    public function cancelPayment($order)
    {
        $result = $this->getOrderRestModel()->cancel($order->getPayment()->getLastTransId());

        if ($result->getStatus() == 'SUCCESS')
            Mage::getModel('super_payments/status')->cancelPaymentByUser($order->getPayment());

        return $result;
    }

    /**
     * @param int $orderId
     * @return string
     */
    public function getTransactionStartUrl($orderId)
    {
        return Mage::helper('super_payments/transaction')->generateUrlWithOrderKey('superpayu/transaction/start', $orderId);
    }

    /**
     * @param int $orderId
     * @return string
     */
    public function getTransactionCancelUrl($orderId)
    {
        return Mage::helper('super_payments/transaction')->generateUrlWithOrderKey('superpayu/transaction/cancel', $orderId);
    }

    /**
     * @param Mage_Sales_Model_Quote|null $quote
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        $isAvailable = parent::isAvailable($quote);

        if (!empty($quote)) {
            // Order total is in payment method amount range
            $total = $quote->getBaseSubtotal() + $quote->getShippingAddress()->getBaseShippingAmount();
            $isAvailable &= $total >= $this->getPaymethodsHelper()->getPriceLowerLimit();
            $isAvailable &= $total <= $this->getPaymethodsHelper()->getPriceUpperLimit();
        }

        return $isAvailable;
    }

    /**
     * @param string $transactionId
     * @param string $status
     */
    protected function updateOrder($transactionId, $status)
    {
        $transaction = $this->getTransaction($transactionId);
        $order = $transaction->getOrder();
        $payment = $order->getPayment()->setTransactionId($transactionId);
        if (empty($order))
            throw new Exception("Nie odnaleziono zamówienia powiązanego z transakcją " . $transactionId);

        $this->updateOrderPayment($payment, $status);
    }

    /**
     * @param Mage_Sales_Model_Order_Payment $payment
     * @param $paymentStatus
     */
    protected function updateOrderPayment($payment, $paymentStatus)
    {
        switch ($paymentStatus) {
            case OpenPayuOrderStatus::STATUS_CANCELED:
                Mage::dispatchEvent($this->getEventPrefix() . "_payment_canceled", array('payment' => $payment));
                break;
            case OpenPayuOrderStatus::STATUS_REJECTED:
                Mage::dispatchEvent($this->getEventPrefix() . "_payment_rejected", array('payment' => $payment));
                break;
            case OpenPayuOrderStatus::STATUS_COMPLETED:
                Mage::dispatchEvent($this->getEventPrefix() . "_payment_completed", array('payment' => $payment));
                break;
        }

        Mage::dispatchEvent($this->getEventPrefix() . "_payment_status_change", array('status' => $paymentStatus, 'payment' => $payment));
    }

    /**
     * @param string $transactionId
     * @return Mage_Sales_Model_Order_Payment_Transaction
     */
    protected function getTransaction($transactionId)
    {
        $transaction = Mage::getModel('sales/order_payment_transaction')
            ->getCollection()
            ->addAttributeToFilter('txn_id', array('eq' => $transactionId))
            ->getFirstItem();

        return $transaction;
    }

    protected abstract function getOrderRestModel();
    protected abstract function getPaymethodsHelper();
    protected abstract function getEventPrefix();
}

