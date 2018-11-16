<?php
/**
 * User: damian
 * Date: 13.11.18
 * Time: 13:07
 */

class Super_Payu_Helper_Paymethods extends Mage_Core_Helper_Abstract
{
    protected $paymentMethods;

    /**
     * Super_Payu_Helper_Paymethods constructor.
     */
    public function __construct()
    {
        $this->paymentMethods = Mage::getModel('super_payu/rest_paymethods')->getMethods();
    }

    /**
     * Get list of payment methods
     * @return array
     */
    public function getPaymentMethods()
    {
        return $this->paymentMethods;
    }

    /**
     * Get list of payment methods codes
     * @return array
     */
    public function getPaymentMethodCodes()
    {
        return array_map(function ($paymentMethod) {
            return $paymentMethod->value;
        }, $this->getPaymentMethods());
    }

    /**
     * Get price lower limit from paymethods
     * @return float
     */
    public function getPriceLowerLimit() {
        $lowerLimit = 0;

        foreach ($this->getPaymentMethods() as $paymentMethod) {
            $lowerLimit = $paymentMethod->minAmount < $lowerLimit ? $paymentMethod->minAmount : $lowerLimit;
        }

        return $lowerLimit / 100;
    }

    /**
     * Get price upper limit from paymethods
     * @return float
     */
    public function getPriceUpperLimit() {
        $upperLimit = 0;

        foreach ($this->getPaymentMethods() as $paymentMethod) {
            $upperLimit = $paymentMethod->maxAmount > $upperLimit ? $paymentMethod->maxAmount : $upperLimit;
        }

        return $upperLimit / 100;
    }
}