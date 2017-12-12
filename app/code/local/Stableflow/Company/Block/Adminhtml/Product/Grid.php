<?php

/**
 * Created by PhpStorm.
 * User: nick
 * Date: 12/10/16
 * Time: 12:36 PM
 */

class Stableflow_Company_Block_Adminhtml_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid{

    public function __construct(){
        parent::__construct();
        $this->setId('productGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * get current entity
     *
     */
    public function getCompany(){
        $id = Mage::getSingleton('adminhtml/session')->getCompanyId();
        return Mage::getModel('company/company')->load($id);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('company/product')->getCollection()
            ->addCompanyFilter($this->getCompany())
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('price')
            ->addAttributeToSelect('is_active')
            ->addAttributeToSelect('catalog_product_id');
        //->addStoreFilter($this->getRequest()->getParam('store'))
//        ->joinField('catalog_product_name',
//            'catalog/product_entity',
//            'value',
//            'product_id=entity_id',
//            null,
//            'left');
        $this->setCollection($collection);

        if ($this->getCompany()->getProductsReadonly()) {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
        }

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('selected_products', array(
            'header'    => $this->__('#'),
            'type'      => 'checkbox',
            'index'     => 'entity_id',
            'align'     => 'center',
            'field_name'=> 'selected_products',
            'values'    => $this->getSelectedProducts(),
        ));
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('catalog')->__('ID'),
            'sortable'  => true,
            'width'     => '60',
            'index'     => 'entity_id'
        ));
        $this->addColumn('catalog_product_id', array(
            'header'    => Mage::helper('catalog')->__('Catalog Product ID'),
            'sortable'  => true,
            'width'     => '60',
            'index'     => 'catalog_product_id'
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('catalog')->__('Name'),
            'index'     => 'name'
        ));
        $this->addColumn('price', array(
            'header'    => Mage::helper('catalog')->__('Price'),
            'type'  => 'currency',
            'width'     => '1',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'     => 'price'
        ));
        $this->addColumn('price_int', array(
            'header'    => Mage::helper('catalog')->__('Price Internal'),
            'type'  => 'currency',
            'width'     => '1',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'     => 'price_int'
        ));
        $this->addColumn('price_wholesale', array(
            'header'    => Mage::helper('catalog')->__('Price Wholesale'),
            'type'  => 'currency',
            'width'     => '1',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'     => 'price_wholesale'
        ));
        $this->addColumn('is_active', array(
            'header'    => Mage::helper('catalog')->__('Active'),
            'index'     => 'is_active',
            'width'     => '60',
            'align'     => 'right',
            'type'      => 'options',
            'options'   => Mage::getSingleton('adminhtml/system_config_source_yesno')->toArray(),
        ));
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('company')->__('Created at'),
                'index' => 'created_at',
                'width' => '120px',
                'type' => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header' => Mage::helper('company')->__('Updated at'),
                'index' => 'updated_at',
                'width' => '120px',
                'type' => 'datetime',
            )
        );
        $this->addColumn('action',array(
                'header'    => Mage::helper('catalog')->__('Edit'),
                'width'     => '5%',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('catalog')->__('Edit Product'),
                        'url'     => array('base'=>'*/*/editProduct'),
                        'popup'   => true,
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true,
            )
        );

        return parent::_prepareColumns();
    }

    protected function _getStore(){
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _getSelectedProducts()
    {
        $products = $this->getRequest()->getPost('selected_products');
        if (is_null($products)) {
            $products = $this->getCompany()->getProductsPosition();
            return array_keys($products);
        }
        return $products;
    }

    protected function _prepareMassaction(){
        //$this->setMassactionIdField('entity_id');
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

    public function getGridUrl()
    {
        return $this->getUrl('*/*/productListGrid', array('_current'=>true));
    }
}