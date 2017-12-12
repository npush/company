<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/23/17
 * Time: 4:47 PM
 */
class Stableflow_Company_Helper_Settings extends Mage_Core_Helper_Data
{
    public function convertToJson($settings)
    {

    }

    public function convertFromJson($settings)
    {
        $empty = array();
        foreach($settings as $key => $value){
            $path = explode('-', $key);
            $empty[$path[0]][$path[1]] = $value;
        }
    }
}