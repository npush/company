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
class Stableflow_Company_Model_Parser_AddCode extends Mage_Core_Model_Abstract
{
    /**
     * Standard resource model init
     */
    protected function _construct()
    {
        $this->_init('company/parser_addCode');
    }

    public function findCode($wrongCode, $companyId)
    {
        return $this->_getResource()->findCode($wrongCode, $companyId);
    }
}