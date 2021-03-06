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
            ->getTasksCollection($this->getCompanyId())
            ->join(array('config_table' => 'company/parser_config'),
                'config_table.entity_id=main_table.config_id',
                array('description')
            );

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
            'header' => Mage::helper('company')->__('Configuration'),
            'index' => 'description',
        ));
        $this->addColumn('file', array(
            'header' => Mage::helper('company')->__('File'),
            'align' => 'left',
            'index' => 'name',
        ));
        $this->addColumn('run_task', array(
            'header'    => Mage::helper('company')->__('Run Task'),
            'width'     => '100px',
            'renderer'  => 'Stableflow_Company_Block_Adminhtml_Parser_Renderer_Action',
            'options' => array(
                'caption' => Mage::helper('company')->__('Run Task'),
                //'url' => array('base' => '*/parser_parser/runTaskImmediately'),
                'window' => 'runTaskImmediately',
                'field' => 'type_id',
            ),
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

            'renderer'  => 'Stableflow_Company_Block_Adminhtml_Parser_Renderer_Action',
            'options' => array(
                'caption' => Mage::helper('company')->__('Edit'),
                //'url' => array('base'=> '*/parser_task/editTask'),
                'window' => 'editTask'

            ),
            'filter'    => false,
            'is_system' => true,
            'sortable'  => false,
        ));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction(){
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('parser_task');
        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('company')->__('Delete'),
            'url'  => $this->getUrl('*/parser_task/massDeleteTask'),
            'confirm'  => Mage::helper('company')->__('Are you sure?')
        ));
        $this->getMassactionBlock()->addItem('status', array(
            'label'      => Mage::helper('company')->__('Change status'),
            'url'        => $this->getUrl('*/parser_task/massStatusTask', array('_current'=>true)),
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