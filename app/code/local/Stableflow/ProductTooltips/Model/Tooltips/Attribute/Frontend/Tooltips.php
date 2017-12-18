<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/20/17
 * Time: 3:54 PM
 */
class Stableflow_ProductTooltips_Model_Tooltips_Attribute_Frontend_Tooltips extends Mage_Eav_Model_Entity_Attribute_Frontend_Abstract
{

    public function getUrl($object, $size=null)
    {
        $url = false;
        $image = $object->getData($this->getAttribute()->getAttributeCode());

        # using original image
        $url = Mage::app()->getStore($object->getStore())->getBaseUrl('media').'catalog/tooltips'.$image;
        return $url;
    }


    public function getValue(Varien_Object $object){
        $imgPath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . Stableflow_ProductTooltips_Block_Tooltip::BASE_TOOLTIPS_PATH;
        $tooltips = $object->getData($this->getAttribute()->getAttributeCode());
        $tooltipArray = null;
        if($tooltips){
            $tooltipIds = explode(',', $tooltips);
            $i = 0;
            foreach($tooltipIds as $tooltipId){
                $modelTooltip = Mage::getModel('product_tooltips/tooltip')->load($tooltipId);
                $tooltipArray[$i] = array(
                    'title' => $modelTooltip->getTitle(),
                    'file' => $imgPath . $modelTooltip->getValue(),
                    'description' => $modelTooltip->getDescription(),
                );
                $i++;
            }
        }
        return $tooltipArray;
    }
}