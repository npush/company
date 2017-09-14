<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/4/17
 * Time: 6:25 PM
 */
class  Stableflow_Company_Helper_Parser extends Mage_Core_Helper_Data
{
    public function getFileBaseUrl()
    {
        return Mage::getBaseUrl('media').'pricelists';
    }

    public function getFileBaseDir()
    {
        return Mage::getBaseDir('media') . DS . 'pricelists';
    }
}