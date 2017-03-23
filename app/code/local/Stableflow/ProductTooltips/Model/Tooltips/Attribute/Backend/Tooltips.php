<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/20/17
 * Time: 3:22 PM
 */

class Stableflow_ProductTooltips_Model_Tooltips_Attribute_Backend_Tooltips extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract{


    /**
     * Prepare data for save
     *
     * @param Varien_Object $object
     * @return Mage_Eav_Model_Entity_Attribute_Backend_Abstract
     */
    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        $data = $object->getData($attributeCode);
        if (is_array($data)) {
            $data = array_filter($data);
            $object->setData($attributeCode, implode(',', $data));
        }

        return parent::beforeSave($object);
    }
}