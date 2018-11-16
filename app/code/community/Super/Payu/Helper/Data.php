<?php
/**
 * User: damian
 * Date: 31.10.18
 * Time: 09:58
 */ 
class Super_Payu_Helper_Data extends Mage_Core_Helper_Abstract {
    const SKIN_CATALOG = 'super/payu/';
    const DESIGN_CATALOG = 'super/payu/';

    /**
     * @param string $fileName
     * @return string
     */
    public function getImageUrl($fileName) {
        return Mage::getDesign()->getSkinUrl('images/' . self::SKIN_CATALOG . $fileName);
    }
}