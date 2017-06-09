<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 6/8/17
 * Time: 3:22 PM
 */
class Stableflow_Company_Block_Pager extends Mage_Page_Block_Html_Pager
{

    public function getPagerUrl($params=array())
    {
        $urlParams = array();
        $urlParams['_current']  = true;
        $urlParams['_escape']   = true;
        $urlParams['_use_rewrite']   = false;
        $urlParams['_query']    = $params;
        return $this->getUrl('*/*/*', $urlParams);
    }
}