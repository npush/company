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

    public function formatUrl($url){
        //preg_match('/^(http|https):\\/\\/[a-z0-9]+([\\-\\.]{1}[a-z0-9]+)*\\.[a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i' ,$url);
        $match = array();
        preg_match('/^(?P<protocol>(http|https):\/\/)?(?P<w>w{3}.)?(?P<domain>[a-z0-9]+([\\-\\.]{1}[a-z0-9]+)*)/', $url, $match);
        if($match['domain'])
            return $match['domain'];
        return $url;
    }
}