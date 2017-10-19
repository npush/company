<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 2/28/17
 * Time: 6:22 PM
 */
class Stableflow_Company_Model_Resource_Product_Collection extends Mage_Eav_Model_Entity_Collection_Abstract{

    /**
     * Relation table (catalog_product_entity to company_product_entity)
     * @var string
     */
    protected $_relationTable = null;

    protected $_companyProductTable = null;

    protected $_productIdArray = null;

    protected function _construct()
    {
        $this->_init('company/product');
        //$this->_relationTable = $this->getTable('company/company_product');
        $this->_companyProductTable = $this->getTable('company/product_entity');
    }

    /**
     * Return id`s
     * @param $company
     */

    public function addCompanyFilter($company)
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()
            ->from($this->_companyProductTable, array('entity_id', 'catalog_product_id'))
            ->where('company_id = :company_id');
        $this->_productIdArray = $adapter->fetchCol($select, array(':company_id' => $company->getId()));
        $this->addFieldToFilter('entity_id', array('in' => $this->_productIdArray));
        return $this;
    }
}