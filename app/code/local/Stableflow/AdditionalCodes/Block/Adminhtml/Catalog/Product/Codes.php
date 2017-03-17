<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/17/17
 * Time: 3:47 PM
 */
class Stableflow_AdditionalCodes_Block_Adminhtml_Catalog_Product_Codes extends Mage_Adminhtml_Block_Template{

    /**
     * Set the template for the block
     *
     */
    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('additional_codes/catalog/product/tab/codes.phtml');
    }

}