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
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection(){
        $collection = Mage::getModel('company/parser_log')
            ->getCollection();

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
        $this->addColumn('task_id', array(
            'header' => Mage::helper('company')->__('Task Id'),
            'index' => 'task_id',
            'type' => 'number'
        ));
        $this->addColumn('error_text', array(
            'header' => Mage::helper('company')->__('Error Text'),
            'index' => 'error_text',
            'type' => 'text'
        ));
        $this->addColumn('status_id', array(
            'header' => Mage::helper('company')->__('Status'),
            'index' => 'status_id',
            'type' => 'number'
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