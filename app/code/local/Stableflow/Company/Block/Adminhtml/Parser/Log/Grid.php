<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/22/17
 * Time: 3:10 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct(){
        parent::__construct();
        $this->setId('logGrid');
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
        $collection = Mage::getModel('company/parser_log')
            ->getLogCollection($this->getCompanyId());

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header' => Mage::helper('company')->__('N'),
            'index' => 'entity_id',
            'type' => 'number'
        ));
        $this->addColumn('task_id', array(
            'header' => Mage::helper('company')->__('Task Id'),
            'index' => 'task_id',
            'type' => 'number'
        ));
        $this->addColumn('line', array(
            'header' => Mage::helper('company')->__('Line (tab:row)'),
            'index' => 'line',
            'type' => 'text'
        ));
        $this->addColumn('error_text', array(
            'header' => Mage::helper('company')->__('Error Code'),
            'index' => 'error_text',
            'type' => 'text'
        ));
        $this->addColumn('raw_data', array(
            'header' => Mage::helper('company')->__('Raw Text'),
            'index' => 'raw_data',
            'type' => 'text'
        ));
        $this->addColumn('created_at', array(
            'header' => Mage::helper('company')->__('Created at'),
            'index'  => 'created_at',
            'width'  => '120px',
            'type'   => 'datetime',
        ));
        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/parser_log/taskLog', array('_current' => true));
    }

    public function getRowUrl()
    {
        return 'alert()';
    }
}