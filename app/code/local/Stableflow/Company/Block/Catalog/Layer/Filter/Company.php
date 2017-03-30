<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/30/17
 * Time: 1:35 PM
 */


class Stableflow_Company_Block_Catalog_Layer_Filter_Sale extends Mage_Catalog_Block_Layer_Filter_Abstract{

    public function __construct(){
        parent::__construct();
        $this->_filterModelName = 'stableflow_company/catalog_layer_filter_company';
    }

}