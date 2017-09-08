<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 4:03 PM
 */
class Stableflow_Company_Model_Parser_Log_Status extends Stableflow_Company_Model_Parser_Status_Abstract
{
    const STATUS_OK             = 1;
    const STATUS_UNKNOWN_ERROR  = 2;
    const STATUS_CODE_NOT_FOUND = 3;

    /**
     * Retrieve option array
     *
     * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::STATUS_OK             => Mage::helper('catalog')->__('Ok'),
            self::STATUS_UNKNOWN_ERROR  => Mage::helper('catalog')->__('UNKNOWN_ERROR'),
            self::STATUS_CODE_NOT_FOUND => Mage::helper('catalog')->__('Code no found'),
        );
    }

}