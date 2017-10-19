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

    public function getManufacturers()
    {
        $res = '';
        $attribute = Mage::getModel('eav/entity_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, Stableflow_Company_Model_Parser_Entity_Product::MANUFACTURER_ATTRIBUTE);
        //$ar = $attribute->getSource()->getOptionArray();
        $ar = $attribute->getSource()->getAllOptions();
        foreach($ar as $name){
            $res .= '"'.str_replace('"', '', $name['label']).'",';
        }
        return "[".rtrim($res, ',')."]";
    }

}