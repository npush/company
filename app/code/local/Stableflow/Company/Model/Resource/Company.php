<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 11:58 AM
 */
class Stableflow_Company_Model_Resource_Company extends Mage_Eav_Model_Entity_Abstract{

    protected $_companyProductTable = null;

    public function _construct(){
        /** @var  $resource Mage_Core_Model_Resource */
        $resource = Mage::getSingleton('core/resource');
        $this->setType('company_company');
        $this->setConnection(
            $resource->getConnection('company_read'),
            $resource->getConnection('company_write')
        );
        //$this->_companyTable = $this->getTable('mageplaza_betterblog/post_category');
    }

    public function getMainTable(){
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

    /**
     * check url key
     *
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     */
    public function checkUrlKey($urlKey, $storeId, $active = true)
    {
        $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID, $storeId);
        $select = $this->_initCheckUrlKeySelect($urlKey, $stores);
        if (!$select) {
            return false;
        }
        $select->reset(Zend_Db_Select::COLUMNS)
            ->columns('e.entity_id')
            ->limit(1);
        return $this->_getReadAdapter()->fetchOne($select);
    }

    /**
     * init the check select
     *
     * @access protected
     * @param string $urlKey
     * @param array $store
     * @return Zend_Db_Select
     * @author Sam
     */
    protected function _initCheckUrlKeySelect($urlKey, $store){
        $urlRewrite = Mage::getModel('eav/config')->getAttribute('company_company', 'url_key');
        if(!$urlRewrite || !$urlRewrite->getId()){
            return false;
        }
        $table = $urlRewrite->getBackend()->getTable();
        $select = $this->_getReadAdapter()->select()
            ->from(array('e' => $table))
            ->where('e.attribute_id = ?', $urlRewrite->getId())
            ->where('e.value = ?', $urlKey)
            ->where('e.store_id IN (?)', $store)
            ->order('e.store_id DESC');
        return $select;
    }

    /**
     * Check for unique URL key
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     * @author Sam
     */
    public function getIsUniqueUrlKey(Mage_Core_Model_Abstract $object)
    {
        if (Mage::app()->isSingleStoreMode() || !$object->hasStores()) {
            $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID);
        } else {
            $stores = (array)$object->getData('stores');
        }
        $select = $this->_initCheckUrlKeySelect($object->getData('url_key'), $stores);
        if ($object->getId()) {
            $select->where('e.entity_id <> ?', $object->getId());
        }
        if ($this->_getWriteAdapter()->fetchRow($select)) {
            return false;
        }
        return true;
    }

    /**
     * Check if the URL key is numeric
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     * @author Sam
     */
    protected function isNumericUrlKey(Mage_Core_Model_Abstract $object){
        return preg_match('/^[0-9]+$/', $object->getData('url_key'));
    }

    /**
     * Check if the URL key is valid
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     * @author Sam
     */
    protected function isValidUrlKey(Mage_Core_Model_Abstract $object){
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('url_key'));
    }

    protected function _updateAttribute($object, $attribute, $valueId, $value){
        $table = $attribute->getBackend()->getTable();
        if(!isset($this->_attributeValuesToSave[$table])){
            $this->_attributeValuesToSave[$table] = array();
        }

        $entityIdField = $attribute->getBackend()->getEntityIdField();

        $data = array(
            'entity_type_id'    => $object->getEntityTypeId(),
            $entityIdField      => $object->getId(),
            'attribute_id'      => $attribute->getId(),
            'value'             => $this->_prepareValueForSave($value, $attribute)
        );

        if($valueId){
            $data['value_id'] = $valueId;
        }

        $this->_attributeValuesToSave[$table][] = $data;

        return $this;
    }
}