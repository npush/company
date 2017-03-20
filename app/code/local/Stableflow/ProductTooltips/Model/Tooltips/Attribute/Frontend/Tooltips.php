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

}