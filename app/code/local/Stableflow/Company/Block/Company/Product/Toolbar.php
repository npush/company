<?php

/**
 * Toolbar.php
 * Free software
 * Project: magento.dev
 *
 * Created by: nick
 * Copyright (C) 2017
 * Date: 3/5/17
 *
 */
class Stableflow_Company_Block_Company_Product_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar{

    /**
     * Return current URL with rewrites and additional parameters
     *
     * @param array $params Query parameters
     * @return string
     */
    public function getPagerUrl($params=array())
    {
        $urlParams = array();
        $urlParams['_current']  = true;
        $urlParams['_escape']   = true;
        $urlParams['_use_rewrite']   = true;
        $urlParams['_query']    = $params;
        return $this->getUrl('company/company/productList', $urlParams);
    }
}