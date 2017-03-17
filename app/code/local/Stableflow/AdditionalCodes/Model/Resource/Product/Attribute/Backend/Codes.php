<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/16/17
 * Time: 4:54 PM
 */
class Stableflow_AdditionalCodes_Model_Resource_Product_Attribute_Backend_Codes extends Mage_Core_Model_Resource_Db_Abstract{

    const CODES_TABLE       = 'additional_codes/product_attribute_additional_codes';

    protected $_eventPrefix = 'additional_codes_product_attribute_backend_codes';

    private $_attributeId = null;

    /**
     * Resource initialization
     */
    protected function _construct(){
        $this->_init(self::CODES_TABLE, 'value_id');
    }

    /**
     * Insert codes value to db and retrive last id
     *
     * @param array $data
     * @return interger
     */
    public function insertAdditionalCode($data)
    {
        $adapter = $this->_getWriteAdapter();
        $data    = $this->_prepareDataForTable(new Varien_Object($data), $this->getMainTable());
        $adapter->insert($this->getMainTable(), $data);

        return $adapter->lastInsertId($this->getMainTable());
    }

    /**
     * Delete codes value in db
     *
     * @param array|integer $valueId
     * @return Mage_Catalog_Model_Resource_Product_Attribute_Backend_Media
     */
    public function deleteAdditionalCode($valueId)
    {
        if (is_array($valueId) && count($valueId)>0) {
            $condition = $this->_getWriteAdapter()->quoteInto('value_id IN(?) ', $valueId);
        } elseif (!is_array($valueId)) {
            $condition = $this->_getWriteAdapter()->quoteInto('value_id = ? ', $valueId);
        } else {
            return $this;
        }

        $this->_getWriteAdapter()->delete($this->getMainTable(), $condition);
        return $this;
    }

    /**
     * Get attribute ID
     *
     * @return int
     */
    protected function _getAttributeId() {
        if(is_null($this->_attributeId)) {
            $attribute = Mage::getModel('eav/entity_attribute')
                ->loadByCode(Mage_Catalog_Model_Product::ENTITY, 'additional_codes');

            $this->_attributeId = $attribute->getId();
        }
        return $this->_attributeId;
    }

    /**
     * Load gallery images for product using reusable select method
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Catalog_Model_Product_Attribute_Backend_Media $object
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

        if ($eventObjectWrapper->hasProductIdsOverride()) {
            $productIds = $eventObjectWrapper->getProductIdsOverride();
        } else {
            $productIds = array($product->getId());
        }

        $select = $this->_getLoadCodesSelect($productIds, $product->getStoreId(), $object->getAttribute()->getId());

        $adapter = $this->_getReadAdapter();
        $result = $adapter->fetchAll($select);
        //$this->_removeDuplicates($result);
        return $result;
    }

    /**
     * Get select to retrieve additional attributes
     * for given product IDs.
     *
     * @param array $productIds
     * @param $storeId
     * @param int $attributeId
     * @return Varien_Db_Select
     */
    protected function _getLoadCodesSelect(array $productIds, $storeId, $attributeId) {
        $adapter = $this->_getReadAdapter();

        //$positionCheckSql = $adapter->getCheckSql('value.position IS NULL', 'default_value.position', 'value.position');

        // Select additional codes for product
        $select = $adapter->select()
            ->from(
                array('main'=>$this->getMainTable()),
                array('value_id', 'value AS file', 'product_id' => 'entity_id')
            )
            ->where('main.attribute_id = ?', $attributeId)
            ->where('main.entity_id in (?)', $productIds);
            //->order($positionCheckSql . ' ' . Varien_Db_Select::SQL_ASC);

        return $select;
    }

}