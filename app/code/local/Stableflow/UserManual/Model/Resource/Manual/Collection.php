<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/3/17
 * Time: 1:33 PM
 */
class Stableflow_UserManual_model_resource_Manual_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{

    protected $_manualStoreTable;

    protected $_manualTable;

    public function _construct(){
        $this->_init('user_manual/manual');
        $this->_manualTable = $this->getTable('user_manual/manual');
        $this->_manualStoreTable = $this->getTable('user_manual/manual_value');
    }


    /**
     * init select
     *
     * @return Mage_Review_Model_Resource_Review_Product_Collection
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()
            ->joinLeft(
                array('label' => $this->_manualStoreTable),
                'main_table.value_id = label.value_id and '. Mage::app()->getStore(true)->getId() . ' = label.store_id',
                array('label','description')
            );
        return $this;
    }
}