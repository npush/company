<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/22/17
 * Time: 3:02 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_Task_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct(){
        parent::__construct();
        $this->setId('taskGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * get current entity
     *
     */
    public function getCompanyId()
    {
        return Mage::getSingleton('adminhtml/session')->getCompanyId();
    }

    protected function _prepareCollection(){
        $collection = Mage::getModel('company/parser_task')
            ->getTasksCollection($this->getCompanyId());

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
        $this->addColumn('config_id', array(
            'header' => Mage::helper('company')->__('Configuration Id'),
            'index' => 'config_id',
        ));
        $this->addColumn('file', array(
                'header' => Mage::helper('company')->__('File'),
                'align' => 'left',
                'index' => 'name',
        ));
        $this->addColumn('run_task', array(
            'header'    => Mage::helper('company')->__('Run Task'),
            'width'     => '100px',
            'renderer'  => 'Stableflow_Company_Block_Adminhtml_Parser_Renderer_RunTask'
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
        $this->addColumn('time_spent', array(
                'header' => Mage::helper('company')->__('Spent time'),
                'align' => 'left',
                'index' => 'time_spent',
                'renderer'  => 'Stableflow_Company_Block_Adminhtml_Parser_Renderer_SpentTime'
        ));
        $this->addColumn('last_row', array(
            'header' => Mage::helper('company')->__('Last parsed row'),
            'align' => 'left',
            'index' => 'last_row',
            'renderer'  => 'Stableflow_Company_Block_Adminhtml_Parser_Renderer_ParsedRow'
        ));
        $this->addColumn('status', array(
                'header' => Mage::helper('company')->__('Status'),
                'align' => 'left',
                'index' => 'status_id',
                'type'      => 'options',
                'options'   => Mage::getSingleton('company/parser_task_status')->getOptionArray(),
        ));
        $this->addColumn('action', array(
                'header'  =>  Mage::helper('company')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('company')->__('Edit'),
                        'url' => array('base'=> '*/parser_task/editTask'),
                        'field' => 'task_id',
                        'popup' => true
                    ),
                    array(
                        'caption' => Mage::helper('company')->__('Add To Queue'),
                        'url' => array('base'=> '*/parser_task/addTaskToQueue'),
                        'field' => 'task_id',
                        'popup' => true
                    ),
                    array(
                        'caption' => Mage::helper('company')->__('Delete'),
                        'url' => array('base'=> '*/parser_task/deleteTask'),
                        'field' => 'task_id',
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
        ));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction(){
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('company');
        $this->getMassactionBlock()->addItem('delete', array(
                'label'=> Mage::helper('company')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('company')->__('Are you sure?')
        ));
        $this->getMassactionBlock()->addItem('status', array(
                'label'      => Mage::helper('company')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
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

    public function getGridUrl()
    {
        return $this->getUrl('*/parser_task/task', array('_current'=>true));
    }

//    public function getRowUrl($row)
//    {
//        return $this->getUrl('*/parser_task/editTask', array('id' => $row->getId()));
//    }
}