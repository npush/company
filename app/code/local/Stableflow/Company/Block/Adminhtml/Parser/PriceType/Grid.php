<?php

/**
 * Created by PhpStorm.
 * User: nick
 * Date: 8/25/17
 * Time: 8:27 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_PriceType_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('parser_price_type');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        //$this->setTemplate('company/tab/products.phtml');
    }

    /**
     * get current entity
     *
     */
    public function getCompany()
    {
        return Mage::registry('current_company');
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('company/parser_price_type')->getPriceTypeCollection(Mage::registry('company_id'));

        $this->setCollection($collection);


        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header' => Mage::helper('company')->__('ID'),
            'sortable' => true,
            'width' => '60px',
            'index' => 'entity_id'
        ));
        $this->addColumn('description', array(
            'header' => Mage::helper('company')->__('Type Description'),
            'index' => 'description'
        ));
        $this->addColumn('is_active', array(
            'header' => Mage::helper('company')->__('Status'),
            'index' => 'is_active',
            'width' => '120px',
            'align' => 'right',
            'type'      => 'options',
            'options'   => Mage::getSingleton('company/parser_price_status')->getOptionArray(),
        ));
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('company')->__('Created at'),
                'index' => 'created_at',
                'width' => '120px',
                'type' => 'datetime',
            )
        );

        $this->addColumn('action', array(
                'header' => Mage::helper('company')->__('Edit'),
                'width' => '5%',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('company')->__('Edit Type'),
                        'url' => array('base' => '*/parser_parser/editPriceType'),
                        'popup' => true,
                        'field' => 'id'
                    ),
                    array(
                        'caption' => Mage::helper('company')->__('Delete Type'),
                        'url' => array('base' => '*/parser_parser/deletePriceType'),
                        'popup' => true,
                        'field' => 'id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'is_system' => true,
            )
        );

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/parser_parser/priceType', array('_current' => true));
    }
}