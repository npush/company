<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/31/17
 * Time: 5:12 PM
 */
class Stableflow_Company_Model_Resource_Parser_Queue extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct(){
        $this->_init('company/parser_queue', 'entity_id');
    }

    public function getIdByTaskId($taskId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'entity_id')
            ->where('task_id = :task_id');
        $bind = array(':task_id' => (string)$taskId);
        return $this->_getReadAdapter()->fetchOne($select, $bind);
    }

}