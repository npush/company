<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/3/17
 * Time: 11:31 AM
 */
class Stableflow_UserManual_Block_Adminhtml_Catalog_Product_Manual extends Mage_Adminhtml_Block_Template{

    /**
     * Set the template for the block
     *
     */
    public function _construct()
    {
        parent::_construct();

        $this->setTemplate('user_manual/catalog/product/tab/manual.phtml');
    }
}