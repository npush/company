<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/26/17
 * Time: 4:03 PM
 */
class Stableflow_Company_Block_Adminhtml_Company_Edit_Tab_Parser extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('company_parser_config');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        //$this->setTemplate('company/tab/products.phtml');
    }

    /**
     * get current entity
     *
     */
    public function getCompany(){
        return Mage::registry('current_company');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('company/parser_config')->getConfigCollection($this->getCompany()->getId());

        $this->setCollection($collection);


        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
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
            'header'    => Mage::helper('company')->__('Config String'),
            'index'     => 'config',
            'width'     => '300px',
            'renderer'  => 'Stableflow_Company_Block_Adminhtml_Parser_Renderer_Config'
        ));
        $this->addColumn('is_active', array(
            'header'    => Mage::helper('company')->__('Status'),
            'index'     => 'is_active',
            'width'     => '120px',
            'align'     => 'right'
        ));
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('company')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header' => Mage::helper('company')->__('Updated at'),
                'index'  => 'updated_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn('action',array(
                'header'    => Mage::helper('company')->__('Edit'),
                'width'     => '5%',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('company')->__('Edit Settings'),
                        'url'     => array('base'=>'*/parser_parser/openConfigurationPopup'),
                        'popup'   => true,
                        'field'   => 'id'
                    ),
                    array(
                        'caption' => Mage::helper('company')->__('Delete Configuration'),
                        'url'     => array('base'=>'*/parser_parser/deleteConfig'),
                        'popup'   => true,
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true,
            )
        );

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}