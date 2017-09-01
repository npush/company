<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/30/17
 * Time: 11:29 AM
 */
class Stableflow_Company_Block_Adminhtml_Parser_Editor_Editor extends Mage_Adminhtml_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('company/parser/editor.phtml');
    }

    public function getSchema()
    {

    }
}