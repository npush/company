<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/22/17
 * Time: 3:02 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_TaskList_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct(){
        parent::__construct();
        $this->setId('taskListGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection(){
        $collection = Mage::getModel('company/parser_task')
            ->getTasksCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
                'header' => Mage::helper('company')->__('Id'),
                'index' => 'entity_id',
                'type' => 'number'
        ));
        $this->addColumn('company_name', array(
                'header' => Mage::helper('company')->__('Company'),
                'index' => 'company_name',
                'filter_index'  => 'company_id',
        ));
        $this->addColumn('config_id', array(
                'header' => Mage::helper('company')->__('Configuration Id'),
                'index' => 'config_id',
        ));
        $this->addColumn('file', array(
                'header' => Mage::helper('company')->__('File'),
                'align' => 'left',
                'index' => 'name',
        ));
        $this->addColumn('created_at', array(
                'header' => Mage::helper('company')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
        ));
        $this->addColumn('process_at', array(
                'header'    => Mage::helper('company')->__('Process at'),
                'index'     => 'process_at',
                'width'     => '120px',
                'type'      => 'datetime',
        ));
        $this->addColumn('spent_time', array(
                'header' => Mage::helper('company')->__('Spent time'),
                'align' => 'left',
                'index' => 'spent_time',
        ));
        $this->addColumn('status', array(
                'header' => Mage::helper('company')->__('Status'),
                'align' => 'left',
                'index' => 'status_id',
        ));
        $this->addColumn('action', array(
                'header'  =>  Mage::helper('company')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('company')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    ),
                    array(
                        'caption' => Mage::helper('company')->__('Add To Queue'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    ),
                    array(
                        'caption' => Mage::helper('company')->__('Delete From Queue'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
        ));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction(){
//        $this->setMassactionIdField('entity_id');
//        $this->getMassactionBlock()->setFormFieldName('company');
//        $this->getMassactionBlock()->addItem('delete', array(
//                'label'=> Mage::helper('company')->__('Delete'),
//                'url'  => $this->getUrl('*/*/massDelete'),
//                'confirm'  => Mage::helper('company')->__('Are you sure?')
//        ));
//        $this->getMassactionBlock()->addItem('status', array(
//                'label'      => Mage::helper('company')->__('Change status'),
//                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
//                'additional' => array(
//                    'status' => array(
//                        'name'   => 'status',
//                        'type'   => 'select',
//                        'class'  => 'required-entry',
//                        'label'  => Mage::helper('company')->__('Status'),
//                        'values' => array(
//                            '1' => Mage::helper('company')->__('Enabled'),
//                            '0' => Mage::helper('company')->__('Disabled'),
//                        )
//                    )
//                )
//        ));
        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/parser_task/taskList', array('_current'=>true));
    }
}