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
class Stableflow_AdditionalCodes_Model_Resource_Product_Attribute_Backend_Manufnumber
    extends Mage_Core_Model_Resource_Db_Abstract
{
    const MANUF_CODE_TABLE = 'additional_codes/product_attribute_manufacturer_number';

    protected $_eventPrefix = 'additional_codes_product_attribute_backend_manufacturer_number';

    private $_attributeId = null;

    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init(self::MANUF_CODE_TABLE, 'value_id');
    }

    /**
     * Load manufacturer codes for product using reusable select method
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Stableflow_AdditionalCodes_Model_Product_Attribute_Backend_Manufnumber $object
     * @return array
     */
    public function loadCodes($product, $object)
    {
        $eventObjectWrapper = new Varien_Object(
            array(
                'product' => $product,
                'backend_attribute' => $object
            )
        );
        Mage::dispatchEvent(
            $this->_eventPrefix . '_load_manufacturer_number_before',
            array('event_object_wrapper' => $eventObjectWrapper)
        );

        if ($eventObjectWrapper->hasProductIdsOverride()) {
            $productIds = $eventObjectWrapper->getProductIdsOverride();
        } else {
            $productIds = array($product->getId());
        }

        $select = $this->_getLoadCodeSelect($productIds, $object->getAttribute()->getId());

        $adapter = $this->_getReadAdapter();
        $result = $adapter->fetchAll($select);
        //$this->_removeDuplicates($result);
        return $result;
    }

    /**
     * Get select to retrieve manufacturer codes
     * for given product IDs.
     *
     * @param array $productIds
     * @param int $attributeId
     * @return Varien_Db_Select
     */
    protected function _getLoadCodeSelect(array $productIds, $attributeId) {
        $adapter = $this->_getReadAdapter();

        // Select gallery images for product
        $select = $adapter->select()
            ->from(
                array('main'=>$this->getMainTable()),
                //array('value_id', 'value AS file', 'product_id' => 'entity_id')
                array('value AS code')
            )
            ->where('main.attribute_id = ?', $attributeId)
            ->where('main.entity_id in (?)', $productIds);

        return $select;
    }

    /**
     * Get attribute ID
     *
     * @return int
     */
    protected function _getAttributeId() {
        if(is_null($this->_attributeId)) {
            $attribute = Mage::getModel('eav/entity_attribute')
                ->loadByCode(Mage_Catalog_Model_Product::ENTITY, 'manufacturer_name');

            $this->_attributeId = $attribute->getId();
        }
        return $this->_attributeId;
    }

    /**
     * Remove duplicates
     *
     * @param array $result
     * @return Mage_Catalog_Model_Resource_Product_Attribute_Backend_Media
     */
    protected function _removeDuplicates(&$result)
    {
        $fileToId = array();

        foreach (array_keys($result) as $index) {
            if (!isset($fileToId[$result[$index]['file']])) {
                $fileToId[$result[$index]['file']] = $result[$index]['value_id'];
            } elseif ($fileToId[$result[$index]['file']] != $result[$index]['value_id']) {
                $this->deleteGallery($result[$index]['value_id']);
                unset($result[$index]);
            }
        }

        $result = array_values($result);
        return $this;
    }
}