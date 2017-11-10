<?php

/**
 * Manufnumber.php
 * Free software
 * Project: rulletka.dev
 *
 * Created by: nick
 * Copyright (C) 2017
 * Date: 11/9/17
 *
 */
class Stableflow_Company_Model_Product_Attribute_Backend_Manufnumber extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{
    /**
     * Load attribute data after product loaded
     *
     * @param Mage_Catalog_Model_Product $object
     */
    public function afterLoad($object)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();
        $value = array();
        $value['images'] = array();
        $value['values'] = array();
        $localAttributes = array('label', 'position', 'disabled');

        foreach ($this->_getResource()->loadGallery($object, $this) as $image) {
            foreach ($localAttributes as $localAttribute) {
                if (is_null($image[$localAttribute])) {
                    $image[$localAttribute] = $this->_getDefaultValue($localAttribute, $image);
                }
            }
            $value['images'][] = $image;
        }

        $object->setData($attrCode, $value);
    }

    public function beforeSave($object)
    {

    }

    public function afterSave($object)
    {

    }

    public function addCode()
    {

    }

    public function updateCode()
    {

    }

    public function removeCode()
    {

    }

    public function getCode()
    {

    }

    /**
     * Retrieve resource model
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Attribute_Backend_Media
     */
    protected function _getResource()
    {
        return Mage::getResourceSingleton('catalog/product_attribute_backend_media');
    }

}