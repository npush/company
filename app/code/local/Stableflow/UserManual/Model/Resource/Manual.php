<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/3/17
 * Time: 1:29 PM
 */
class Stableflow_UserManual_Model_Resource_Manual extends Mage_Core_Model_Resource_DB_Abstract{

    protected $_manualStoreTable;

    protected $_manualTable;

    public function _construct(){
        $this->_init('user_manual/manual', 'value_id');
        $this->_manualTable = $this->getTable('user_manual/manual');
        $this->_manualStoreTable = $this->getTable('user_manual/manual_value');
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Mage_Rating_Model_Rating $object
     * @return Varien_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object){
        $select = parent::_getLoadSelect($field, $value, $object);

        $select->joinLeft(
            array('label' => $this->_manualStoreTable),
            'main_table.value_id = label.value_id and '. Mage::app()->getStore(true)->getId() . ' = label.store_id',
            array('label','description')
        );
        return $select;

//        $adapter    = $this->_getReadAdapter();
//
//        $table      = $this->getMainTable();
//        $storeId    = (int)Mage::app()->getStore()->getId();
//        $select     = parent::_getLoadSelect($field, $value, $object);
//        $codeExpr   = $adapter->getIfNullSql('title.value', "{$table}.rating_code");
//
//        $select->joinLeft(
//            array('title' => $this->getTable('rating/rating_title')),
//            $adapter->quoteInto("{$table}.rating_id = title.rating_id AND title.store_id = ?", $storeId),
//            array('rating_code' => $codeExpr));
//
//        return $select;
    }

    /**
     * Actions after load
     *
     * @param Mage_Rating_Model_Rating $object
     * @return Mage_Rating_Model_Resource_Rating
     */
    public function afterLoad(Mage_Core_Model_Abstract $object)
    {
        parent::afterLoad($object);

        if (!$object->getId()) {
            return $this;
        }

        $adapter = $this->_getReadAdapter();
        $bind    = array(':value_id' => (int)$object->getId());
        // load rating titles
        $select  = $adapter->select()
            ->from($this->_manualStoreTable, array('store_id', 'label'))
            ->where('value_id=:value_id');

        $result  = $adapter->fetchPairs($select, $bind);
        if ($result) {
            $object->setRatingCodes($result);
        }

        // load rating available in stores
        $object->setStores($this->getStores((int)$object->getId()));

        return $this;
    }

    protected function _afterSave(Mage_Core_Model_Abstract $object){
        $adapter = $this->_getWriteAdapter();
        /**
         * save detail
         */
        $detail = array(
            'label'     => $object->getLabel(),
            'description'    => $object->getDescription(),
            'store_id'  => $object->getStoreId(),
        );
        $select = $adapter->select()
            ->from($this->_manualStoreTable, 'value_id')
            ->where('value_id = :value_id');
//print_r($object->getId());die();
        $detailId = $adapter->fetchOne($select, array(':value_id' => $object->getId()));
        if ($detailId) {
            $condition = array("value_id = ?" => $detailId);
            $adapter->update($this->_manualStoreTable, $detail, $condition);
        } else {
            $detail['value_id']  = $object->getId();
            $adapter->insert($this->_manualStoreTable, $detail);
        }
        return $this;
    }
}