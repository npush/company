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
class Stableflow_Company_Model_Product_Attribute_Backend_Manufnumber extends
    Mage_Eav_Model_Entity_Attribute_Backend_Abstract
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
        foreach ($this->_getResource()->loadCodes($object, $this) as $code) {
            $value[] = $code['code'];
        }
        $object->setData($attrCode, $value);
    }

    public function beforeSave($object)
    {
        return parent::beforeSave($object);
    }

    public function afterSave($object)
    {
        return parent::afterSave($object);
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
     * @return Stableflow_Company_Model_Resource_Product_Attribute_Backend_Manufnumber
     */
    protected function _getResource()
    {
        return Mage::getResourceSingleton('company/product_attribute_backend_manufnumber');
    }

    /**
     * Get table name for the values of the attribute
     *
     * @return string
     */
    public function getTable()
    {
        return 'catalog_product_entity_manufacturer_number';
    }

}