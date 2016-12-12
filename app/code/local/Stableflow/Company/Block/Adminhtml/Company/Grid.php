<?php

/**
 * Created by PhpStorm.
 * User: nick
 * Date: 12/10/16
 * Time: 12:36 PM
 */

class Stableflow_Company_Block_Adminhtml_Company_Grid extends Mage_Adminhtml_Block_Widget_Grid{

    public function __construct(){
        parent::__construct();
        $this->setId('companyGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection(){
        $collection = Mage::getModel('company/company')
            ->getCollection()
            ->addAttributeToSelect('status')
            ->addAttributeToSelect('url_key');

        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $store = $this->_getStore();

        /*$collection->joinAttribute(
            'post_title',
            'mageplaza_betterblog_post/post_title',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        if ($store->getId()) {
            $collection->joinAttribute(
                'mageplaza_betterblog_post_post_title',
                'mageplaza_betterblog_post/post_title',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
        }*/

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns(){
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('company')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'post_title',
            array(
                'header'    => Mage::helper('company')->__('Name'),
                'align'     => 'left',
                'index'     => 'post_title',
            )
        );

        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('company')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('company')->__('Enabled'),
                    '0' => Mage::helper('company')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'url_key',
            array(
                'header' => Mage::helper('company')->__('URL key'),
                'index'  => 'url_key',
            )
        );
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('company')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('company')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
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
                        'caption' => Mage::helper('company')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('company')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('company')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('company')->__('XML'));
        return parent::_prepareColumns();
    }

    protected function _getStore(){
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareMassaction(){
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('company');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('company')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('company')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
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
            )
        );
        return $this;
    }

    public function getRowUrl($row){
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }


    public function getGridUrl(){
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}