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

        $this->setId('parserPriceTypeGrid');
        $this->setDefaultSort('entity_id');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        //$this->setTemplate('company/tab/products.phtml');
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
        $collection = Mage::getModel('company/parser_price_type')->getPriceTypeCollection($this->getCompanyId());

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

                'renderer'  => 'Stableflow_Company_Block_Adminhtml_Parser_Renderer_Action',
                'options' => array(
                    'caption' => Mage::helper('company')->__('Edit Type'),
                    //'url' => array('base' => '*/parser_parser/editPriceType'),
                    'window' => 'editPriceType',
                    'field' => 'type_id',
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

//    public function getRowUrl($row)
//    {
//        return $this->getUrl('*/parser_parser/editPriceType', array('type_id' => $row->getId()));
//    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('parser_price_type');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('company')->__('Delete'),
            'url'  => $this->getUrl('*/parser_parser/massDeletePriceType'),
            'confirm' => Mage::helper('company')->__('Are you sure?')
        ));
        $this->getMassactionBlock()->addItem('status', array(
            'label'      => Mage::helper('company')->__('Change status'),
            'url'        => $this->getUrl('*/parser_parser/massStatusPriceType', array('_current'=>true)),
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