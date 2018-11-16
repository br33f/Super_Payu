<?php
/**
 * User: damian
 * Date: 05.11.18
 * Time: 11:10
 */

require_once('vendor/openpayu/openpayu/lib/openpayu.php');

class Super_Payu_Model_Rest_Order
{
    /**
     * @var Super_Payu_Model_Rest_Config
     */
    protected $restConfig;

    /**
     * @var Super_Payu_Helper_Config
     */
    protected $helperConfig;

    /**
     * Super_Payu_Model_Rest_Order constructor.
     */
    public function __construct()
    {
        $this->restConfig = Mage::getModel('super_payu/rest_config');
        $this->helperConfig = Mage::helper('super_payu/config');
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return OpenPayU_Result $result
     * @throws OpenPayU_Exception
     */
    public function create($order)
    {
        $orderData = $this->prepareCreateOrderData($order);

        $this->restConfig->initPayuConfiguration();
        $result = OpenPayU_Order::create($orderData);

        return $result;
    }

    /**
     * @param Mage_Sales_Model_Order $order
     */
    protected function prepareCreateOrderData($order)
    {
        $productList = array();
        foreach ($order->getAllItems() as $item) {
            $productList[] = array(
                'quantity' => round($item->getQtyOrdered()),
                'name' => $item->getName(),
                'unitPrice' => $this->convertPrice($item->getPriceInclTax())
            );
        }
        if ($order->getShippingInclTax() > 0) {
            $productList[] = array(
                'quantity' => 1,
                'name' => $order->getShippingDescription(),
                'unitPrice' => $this->convertPrice($order->getShippingInclTax())
            );
        }

        $buyer = array(
            'email' => $order->getCustomerEmail(),
            'firstName' => $order->getBillingAddress()->getFirstname()
        );

        return array(
            'description' => Mage::helper('super_payu')->__('Zamówienie #%s', $order->getRealOrderId()),
            'products' => $productList,
            'buyer' => $buyer,
            'merchantPosId' => $this->helperConfig->getPosId(),
            'customerIp' => $order->getRemoteIp(),
            'continueUrl' => $this->getContinueUrl($order->getId()),
            'notifyUrl' => $this->getNotifyUrl(),
            'currencyCode' => $order->getOrderCurrencyCode(),
            'totalAmount' => $this->convertPrice($order->getGrandTotal()),
            'settings' => array(
                'invoiceDisabled' => true
            )
        );
    }

    /**
     * @param $price
     * @return int
     */
    protected function convertPrice($price)
    {
        return (int)round($price * 100);
    }

    /**
     * @throws OpenPayU_Exception
     */
    public function consumeNotification()
    {
        $body = file_get_contents('php://input');
        $data = trim($body);

        $this->restConfig->initPayuConfiguration();
        return OpenPayU_Order::consumeNotification($data);
    }

    /**
     * @param string $transactionId
     * @return OpenPayU_Result $result
     * @throws OpenPayU_Exception
     */
    public function cancel($transactionId)
    {
        $this->restConfig->initPayuConfiguration();
        $result = OpenPayU_Order::cancel($transactionId);

        return $result;
    }

    /**
     * @return string
     */
    protected function getNotifyUrl()
    {
        return Mage::getUrl('superpayu/transaction/notify');
    }

    /**
     * @param int $orderId
     * @return string
     */
    protected function getContinueUrl($orderId)
    {
        return Mage::helper('super_payments/payment')->generatePaymentContinueUrl($orderId);
    }
}