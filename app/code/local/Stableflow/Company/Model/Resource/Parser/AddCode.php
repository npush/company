<?php

/**
 * AddCode.php
 * Free software
 * Project: rulletka.dev
 *
 * Created by: nick
 * Copyright (C) 2017
 * Date: 11/13/17
 *
 */
class Stableflow_Company_Model_Resource_Parser_AddCode  extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('company/parser_additional_code', 'entity_id');
    }

    /**
     * Find
     *
     * @param int $companyId
     * @param string $wrongCode
     * @return string code
     */
    public function findCode($wrongCode, $companyId)
    {
        $adapter = $this->_getReadAdapter();
        $select = $adapter->select()
            ->from($this->getMainTable(), array('base_code'))
            ->where('company_id = :company_id')
            ->where('wrong_code = :wrong_code');
        $bind = array(
            ':company_id'    => $companyId,
            ':wrong_code'     => $wrongCode,
        );
        return $adapter->fetchOne($select, $bind);
    }
}