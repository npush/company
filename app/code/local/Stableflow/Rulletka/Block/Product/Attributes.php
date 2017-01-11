<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 1/11/17
 * Time: 12:31 PM
 */

class Stableflow_Rulletka_Block_Product_Attributes extends Mage_Core_Block_Template
{

    public function getAttributes($_product){
        $attributeGroupId = null;
        $setId = $_product->getAttributeSetId(); // Attribute set Id
        $groups = Mage::getModel('eav/entity_attribute_group')
            ->getResourceCollection()
            ->setAttributeSetFilter($setId)
            ->setSortOrder()
            ->load();
        foreach ($groups as $group) {
            if($group->getAttributeGroupName() == 'Rulletka'){
                $attributeGroupId = $group->getId();
            }
        }
        $attributes = $_product->getAttributes($attributeGroupId);
        $out = '';
        foreach ($attributes as $attribute){
            $group = $attribute->getAttributeGroupId();
            $label = $attribute->getStoreLabel();
            $value = $attribute->getFrontend()->getValue($_product);
            if($value && $label){
                $out .="<p>{$label}:&nbsp<span class=\"bold\">{$value}</span></p>";
            }
        }
    }
}