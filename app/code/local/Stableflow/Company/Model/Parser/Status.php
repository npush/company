<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:03 PM
 */
class Stableflow_Company_Model_Parser_Status extends Stableflow_Company_Model_Parser_Status_Abstract
{
    const STATUS_ENABLED    = 1;
    const STATUS_DISABLED   = 2;

    /**
     * Retrieve option array
     *
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('catalog')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('catalog')->__('Disabled')
        );
    }

    /**
     * Update status value
     *
     * @param   int $taskId
     * @param   int $value
     * @return  Stableflow_Company_Model_Parser_Status
     */
    public function updateParserStatus($taskId, $value)
    {
        return $this;
    }
}