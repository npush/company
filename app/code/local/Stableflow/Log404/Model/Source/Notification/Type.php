<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 6/19/17
 * Time: 12:14 PM
 */
class Stableflow_Log404_Model_Source_Notification_Type
{
    public function toOptionArray()
    {
        $helper = Mage::helper('sf_log404');
        $optionArray = array(
            '0' => $helper->__('Log File'),
            '1' => $helper->__('Database'),
            '2' => $helper->__('Log File + Database')
        );
        return $optionArray;
    }
}