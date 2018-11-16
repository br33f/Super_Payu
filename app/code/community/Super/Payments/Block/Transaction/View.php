<?php
/**
 * User: damian
 * Date: 31.10.18
 * Time: 13:26
 */

class Super_Payments_Block_Transaction_View extends Super_Payments_Block_Transaction_Abstract
{
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->__('Płatności dla zamówienia #%d', $this->getOrder()->getIncrementId());
    }
}