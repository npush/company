<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:17 PM
 */
class Stableflow_Company_Model_Parser_Log extends Mage_Core_Model_Abstract
{

    protected $_taskId = null;

    /**
     * Standard resource model init
     */
    protected function _construct()
    {
        $this->_init('company/parser_log');
    }

    public function getErrorsLinesIds($taskId = null)
    {

    }
}