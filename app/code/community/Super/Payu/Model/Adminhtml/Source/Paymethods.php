<?php
/**
 * User: damian
 * Date: 13.11.18
 * Time: 12:42
 */

require_once('vendor/openpayu/openpayu/lib/openpayu.php');

class Super_Payu_Model_Adminhtml_Source_Paymethods
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array_map(function ($paymentMethod) {
            return array('value' => $paymentMethod->value, 'label' => $paymentMethod->name);
        }, $this->getPaymentMethods());
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $indexedArray = array();

        foreach ($this->getPaymentMethods() as $paymentMethod) {
            $indexedArray[$paymentMethod->value] = $paymentMethod->name;
        }

        return $indexedArray;
    }

    /**
     * Get list of payment methods
     * @return array
     */
    protected function getPaymentMethods()
    {
        Mage::helper('super_payu/paymethods')->getInstallmentPaymentMethodCodes();

        return Mage::getModel('super_payu/rest_paymethods')->getMethods();
    }
}