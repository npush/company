<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/31/17
 * Time: 5:30 PM
 */
class Stableflow_Company_Model_Parser_Config_Settings extends Varien_Object
{
    public function getParser()
    {
        return $this->getData('parser');
    }
}