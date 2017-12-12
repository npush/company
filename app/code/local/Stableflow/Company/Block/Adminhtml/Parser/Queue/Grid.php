<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/22/17
 * Time: 2:51 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_Queue_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('queueGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('company/parser_queue')
            ->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('company')->__('Id'),
                'index' => 'entity_id',
                'type' => 'number'
            )
        );
        $this->addColumn(
            'task',
            array(
                'header' => Mage::helper('company')->__('Task'),
                'align' => 'left',
                'index' => 'task_id',
            )
        );
        $this->addColumn(
            'status',
            array(
                'header' => Mage::helper('company')->__('Status'),
                'align' => 'left',
                'index' => 'status_id',
                //'renderer'  => 'Stableflow_Company_Block_Adminhtml_Parser_Renderer_Status',
                'type'      => 'options',
                'options'   => Mage::getSingleton('company/parser_queue_status')->getOptionArray(),
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('company')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('company')->__('Delete from Queue'),
                        'url'     => array('base'=> '*/parser_queue/delete'),
                        'field'   => 'id'
                    ),
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );

        return parent::_prepareColumns();
    }
}