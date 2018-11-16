<?php
/**
 * User: damian
 * Date: 31.10.18
 * Time: 10:43
 */

class Super_Payu_Block_Form extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();

        $this->setMethodTitle($this->getPaymentTitle());
        $this->setMethodLabelAfterHtml('<img src="' . $this->getPaymentIcon() . '" alt="' . $this->getPaymentTitle() . '" class="super-payu-logo" />');
        $this->setTemplate(Super_Payu_Helper_Data::DESIGN_CATALOG . 'payment/form.phtml');
    }

    /**
     * @return string
     */
    protected function getPaymentTitle()
    {
        return Mage::helper('super_payu/config')->getPaymentTitle();
    }

    /**
     * @return string
     */
    protected function getPaymentIcon()
    {
        return Mage::helper('super_payu')->getImageUrl('payu_icon.png');
    }

    protected function _prepareLayout()
    {
        if ($head = $this->getLayout()->getBlock('head')) {
            $head->addCss('css/' . Super_Payu_Helper_Data::SKIN_CATALOG . 'payu.css');
        }

        return parent::_prepareLayout();
    }


}