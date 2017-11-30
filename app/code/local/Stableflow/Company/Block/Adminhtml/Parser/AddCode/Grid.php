<?php

/**
 * Grid.php
 * Free software
 * Project: rulletka.dev
 *
 * Created by: nick
 * Copyright (C) 2017
 * Date: 8/25/17
 *
 */
class Stableflow_Company_Block_Adminhtml_Parser_AddCode_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('parser_addCodes');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    /**
     * get current entity
     *
     */
    public function getCompanyId()
    {
        return Mage::getSingleton('adminhtml/session')->getCompanyId();
    }

    protected function _prepareCollection()
    {
        //$collection = Mage::getModel('company/parser_addCodes')->getConfigCollection($this->getCompanyId());
        $collection = Mage::getModel('company/parser_addCode')->getCollection()
            ->addFieldToFilter('company_id', array('eq' => $this->getCompanyId()));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('company')->__('N'),
            'sortable'  => true,
            'width'     => '60px',
            'index'     => 'entity_id'
        ));
        $this->addColumn('base_code', array(
            'header'    => Mage::helper('company')->__('Base code'),
            'sortable'  => true,
            'width'     => '60px',
            'index'     => 'base_code'
        ));
        $this->addColumn('wrong_code', array(
            'header'    => Mage::helper('company')->__('Wrong code'),
            'sortable'  => true,
            'width'     => '60px',
            'index'     => 'wrong_code'
        ));
        $this->addColumn('base_company_name', array(
            'header'    => Mage::helper('company')->__('Base company name'),
            'sortable'  => true,
            'width'     => '60px',
            'index'     => 'base_company_name'
        ));
        $this->addColumn('wrong_company_name', array(
            'header'    => Mage::helper('company')->__('Wrong company name'),
            'sortable'  => true,
            'width'     => '60px',
            'index'     => 'wrong_company_name'
        ));
        return parent::_prepareColumns();
    }

    /**
     * Determine ajax url for grid refresh
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/parser_parser/companyCode', array('_current'=>true));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('parser_addcode');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('company')->__('Delete'),
            'url'  => $this->getUrl('*/parser_parser/massDeleteConfiguration'),
            'confirm' => Mage::helper('company')->__('Are you sure?')
        ));
        return $this;
    }
}