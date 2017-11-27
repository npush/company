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
class Stableflow_Company_Block_Adminhtml_Parser_AddCodes_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $collection = Mage::getModel('company/parser_addCode')->getCollection();

        $this->setCollection($collection);


        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
//        $this->addColumn('select', array(
//            'header_css_class'  => 'a-center',
//            'type'              => 'checkbox',
//            'name'              => 'select',
//            'values'            => '',
//            'align'             => 'center',
//            'index'             => 'entity_id'
//        ));
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('company')->__('ID'),
            'sortable'  => true,
            'width'     => '60px',
            'index'     => 'entity_id'
        ));

        $this->addColumn('action',array(
                'header'    => Mage::helper('company')->__('Edit'),
                'width'     => '5%',

                'renderer'  => 'Stableflow_Company_Block_Adminhtml_Parser_Renderer_Action',
                'options'   =>
                    array(
                        'caption' => Mage::helper('company')->__('Edit Configuration'),
                        //'url'     => array('base' => '*/parser_parser/editConfiguration'),
                        'window' => 'editConfiguration',
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

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('parser_config');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('company')->__('Delete'),
            'url'  => $this->getUrl('*/parser_parser/massDeleteConfiguration'),
            'confirm' => Mage::helper('company')->__('Are you sure?')
        ));
        $this->getMassactionBlock()->addItem('status', array(
            'label'      => Mage::helper('company')->__('Change status'),
            'url'        => $this->getUrl('*/parser_parser/massStatusConfiguration', array('_current'=>true)),
            'additional' => array(
                'status' => array(
                    'name'   => 'status',
                    'type'   => 'select',
                    'class'  => 'required-entry',
                    'label'  => Mage::helper('company')->__('Status'),
                    'values' => array(
                        '1' => Mage::helper('company')->__('Enabled'),
                        '0' => Mage::helper('company')->__('Disabled'),
                    )
                )
            )
        ));
        return $this;
    }
}