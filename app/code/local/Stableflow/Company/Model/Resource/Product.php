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
    }

    public function getMainTable()
    {
        return $this->getEntityTable();
    }

    protected function _getDefaultAttributes(){
        return [
            'entity_id',
            'entity_type_id',
            'attribute_set_id',
            'created_at',
            'updated_at',
            'increment_id',
            'store_id',
            'is_active'
            //'website_id'
        ];
    }

    public function addCompanyFilter(Stableflow_Company_Model_Company $company)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->_relationTable, array('product_id', 'company_product_id'))
            ->where('company_id = :company_id');
        $this->_productIdArray = $adapter->fetchAll($select, array(':company_id' => $company->getId()));
        return $this->_productIdArray;
    }
}