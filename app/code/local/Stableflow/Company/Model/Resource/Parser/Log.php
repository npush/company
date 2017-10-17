<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/5/17
 * Time: 12:40 PM
 */
class Stableflow_Company_Model_Resource_Parser_Log extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('company/parser_log', 'entity_id');
    }

    /**
     * Clean up log table.
     *
     * @param int $lifetime Lifetime of entries in days
     * @return Stableflow_Company_Model_Resource_Parser_Log
     */
    public function clean($lifetime) {
        $cleanTime = $this->formatDate(time() - $lifetime * 3600 * 24, false);
        $readAdapter    = $this->_getReadAdapter();
        $writeAdapter   = $this->_getWriteAdapter();
        $select = $readAdapter->select()->from(
            $this->getMainTable(),
            $this->getIdFieldName()
        )
            ->where('log_at < ?', $cleanTime)
            ->order('log_at ASC')
            ->limit(100);
        while($logIds = $readAdapter->fetchCol($select)){
            $condition = array($this->getIdFieldName() . ' IN (?)' => $logIds);
            $writeAdapter->delete($this->getMainTable(), $condition);
        }

        return $this;
    }
}