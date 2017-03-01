<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 6:31 PM
 */
class Stableflow_Company_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getFileBaseUrl()
    {
        return Mage::getBaseUrl('media').'company'.'/'.'file';
    }
}