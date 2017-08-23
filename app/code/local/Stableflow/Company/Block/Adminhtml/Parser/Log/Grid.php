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
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('company')->__('Id'),
                'index' => 'entity_id',
                'type' => 'number'
            )
        );
        return parent::_prepareColumns();
    }
}