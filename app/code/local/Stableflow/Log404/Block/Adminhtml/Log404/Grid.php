<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 6/19/17
 * Time: 4:26 PM
 */

class Stableflow_Log404_Block_Adminhtml_Log404_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $collection;

    public function __construct()
    {
        parent::__construct();

        // Set some defaults for our grid
        $this->setDefaultSort('id');
        $this->setId('sf_log404_grid');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _getCollectionClass()
    {
        return 'sf_log404/log404_collection';
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id',
            array(
                'header'=> $this->__('ID'),
                'align' =>'right',
                'width' => '50px',
                'index' => 'entity_id'
            )
        );

        $this->addColumn('requested_url',
            array(
                'header'=> $this->__('Requested Url'),
                'align' =>'right',
                'width' => '600px',
                'index' => 'requested_url'
            )
        );

        $this->addColumn('referrer_url',
            array(
                'header'=> $this->__('Referrer Url'),
                'align' =>'right',
                'width' => '600px',
                'index' => 'referrer_url'
            )
        );

        $this->addColumn('description',
            array(
                'header'=> $this->__('Description'),
                'align' =>'right',
                'width' => '180px',
                'index' => 'description'
            )
        );


        $this->addColumn('created_at',
            array(
                'header'=> $this->__('Created At'),
                'align' =>'right',
                'width' => '170px',
                'index' => 'created_at'
            )
        );
        return parent::_prepareColumns();
    }

}