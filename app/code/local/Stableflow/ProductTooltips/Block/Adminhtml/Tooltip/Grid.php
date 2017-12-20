<?php

/**
 * Created by PhpStorm.
 * User: nick
 * Date: 12/10/16
 * Time: 12:36 PM
 */

class Stableflow_ProductTooltips_Block_Adminhtml_Tooltip_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('tooltipGrid');
        $this->setDefaultSort('tooltip_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('product_tooltips/tooltip')
            ->getCollection()
            //->addAttributeToSelect('*')
        ;

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
            'tooltip_id',
            array(
                'header' => Mage::helper('product_tooltips')->__('Id'),
                'index'  => 'tooltip_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'value',
            array(
                'header'    => Mage::helper('product_tooltips')->__('Image'),
                'align'     => 'left',
                'index'     => 'value',
                'width'     => '97px',
                'renderer' => 'Stableflow_ProductTooltips_Block_Adminhtml_Template_Grid_Renderer_Image'
            )
        );
        $this->addColumn(
            'title',
            array(
                'header'    => Mage::helper('product_tooltips')->__('Title'),
                'align'     => 'left',
                'index'     => 'title',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('product_tooltips')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('product_tooltips')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        return parent::_prepareColumns();
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('tooltip_id');
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

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }


    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}