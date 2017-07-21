<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 2/28/17
 * Time: 6:21 PM
 */
class Stableflow_Company_Model_Resource_Product extends Mage_Eav_Model_Entity_Abstract
{
    /**
     * Relation table (catalog_product_entity to company_product_entity)
     * @var string
     */
    protected $_relationTable = null;

    protected $_companyProductTable = null;

    protected $_productIdArray = null;

    public function _construct()
    {
        /** @var  $resource Mage_Core_Model_Resource */
        $resource = Mage::getSingleton('core/resource');
        $this->setType('company_product');
        $this->setConnection(
            $resource->getConnection('company_read'),
            $resource->getConnection('company_write')
        );
        $this->_relationTable = $this->getTable('company/company_product');
        $this->_companyProductTable = $this->getTable('company/product_entity');
    }

    public function getMainTable()
    {
        return $this->getEntityTable();
    }

    protected function _getDefaultAttributes(){
        return [
            'entity_id',
            'entity_type_id',
            'company_id',
            'catalog_product_id',
            'attribute_set_id',
            'created_at',
            'updated_at',
            'increment_id',
            'store_id',
            'is_active'
            //'website_id'
        ];
    }

    public function addCompanyFilter($companyId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->_companyProductTable, array('entity_id', 'catalog_product_id'))
            ->where('company_id = :company_id');
        $this->_productIdArray = $adapter->fetchAll($select, array(':company_id' => $companyId));
        return $this->_productIdArray;
    }

    public function addCatalogProductFilter($catalogProductId){
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->_companyProductTable, 'company_id')
            ->where('catalog_product_id =:catalog_product_id');
        return $adapter->fetchAll($select, array(':catalog_product_id' => $catalogProductId));
    }

    public function _getProductId($catalogProductId, $companyId){
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->_companyProductTable, 'entity_id')
            ->where('catalog_product_id =:catalog_product_id')
            ->where('company_id =:company_id');
        return $adapter->fetchOne($select, array(
            ':catalog_product_id' => $catalogProductId,
            ':company_id'   => $companyId
        ));
    }

    public function _getCatalogProductIds ($companyId){
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->_companyProductTable, 'catalog_product_id')
            ->where('company_id =:company_id');
        return $adapter->fetchAll($select, array(
            ':company_id'   => $companyId
        ));
    }
}