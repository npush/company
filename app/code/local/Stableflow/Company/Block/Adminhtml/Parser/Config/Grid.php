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
class Stableflow_Company_Block_Adminhtml_Parser_Config_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('parser_configuration');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    /**
     * get current entity
     *
     */
    public function getCompanyId(){
        return Mage::getSingleton('adminhtml/session')->getCompanyId();
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('company/parser_config')->getConfigCollection($this->getCompanyId());

        $this->setCollection($collection);


        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('select', array(
            'header_css_class'  => 'a-center',
            'type'              => 'checkbox',
            'name'              => 'select',
            'values'            => '',
            'align'             => 'center',
            'index'             => 'entity_id'
        ));
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('company')->__('ID'),
            'sortable'  => true,
            'width'     => '60px',
            'index'     => 'entity_id'
        ));
        $this->addColumn('price_type', array(
            'header'    => Mage::helper('company')->__('Parser Price Type'),
            'index'     => 'type_description'
        ));
        $this->addColumn('description', array(
            'header'    => Mage::helper('company')->__('Config Description'),
            'index'     => 'description'
        ));
        $this->addColumn('config', array(
            'header'    => Mage::helper('company')->__('Setting String'),
            'index'     => 'config',
            'width'     => '300px',
            'renderer'  => 'Stableflow_Company_Block_Adminhtml_Parser_Renderer_Config'
        ));
        $this->addColumn('is_active', array(
            'header'    => Mage::helper('company')->__('Status'),
            'index'     => 'is_active',
            'width'     => '120px',
            'align'     => 'right',
            'type'      => 'options',
            'options'   => Mage::getSingleton('company/parser_config_status')->getOptionArray(),
        ));
        $this->addColumn('created_at', array(
                'header' => Mage::helper('company')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
        ));
        $this->addColumn('updated_at', array(
                'header' => Mage::helper('company')->__('Updated at'),
                'index'  => 'updated_at',
                'width'  => '120px',
                'type'   => 'datetime',
        ));
        $this->addColumn('action',array(
                'header'    => Mage::helper('company')->__('Edit'),
                'width'     => '5%',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('company')->__('Edit Configuration'),
                        'url'     => array('base' => '*/parser_parser/editConfiguration'),
                        'popup'   => true,
                        'field'   => 'config_id'
                    ),
                    array(
                        'caption' => Mage::helper('company')->__('Delete Configuration'),
                        'url'     => array('base' => '*/parser_parser/deleteConfiguration'),
                        'field'   => 'config_id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true,
            )
        );

        return parent::_prepareColumns();
    }

    /**
     * Determine ajax url for grid refresh
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/parser_parser/parserConfiguration', array('_current'=>true));
    }
}