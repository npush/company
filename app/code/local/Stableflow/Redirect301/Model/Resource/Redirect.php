<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 5/18/17
 * Time: 11:11 AM
 */
class Stableflow_Redirect301_Model_Resource_Redirect extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct(){
        $this->_init('redirect301/ref_table', 'product_id');
    }

    public function getRedirect($old_id){
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('redirect301/ref_table'),'entity_id')
            ->where('product_id = ?', $old_id);
        return $this->_getReadAdapter()->fetchOne($select);

    }
}