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
     * Check block is readonly.
     *
     * @return boolean
     */
    public function isReadonly(){
        $customer = Mage::registry('current_company');
        return $customer->isReadonly();
    }

    /**
     * get current entity
     *
     */
    public function getCompany(){
        //return Mage::registry('current_company');
        return Mage::getModel('company/company')->load(1);
    }

    protected function _prepareCollection()
    {
        if ($this->getCompany()->getId()) {
            /*$this->setDefaultFilter(array(
                'in_category'   => 1
            ));*/
        }
        $collection = Mage::getModel('company/product')->getCollection()
            ->addCompanyFilter($this->getCompany())
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('price')
            ->addAttributeToSelect('is_active')
            ->addAttributeToSelect('catalog_product_id');
        //->addStoreFilter($this->getRequest()->getParam('store'))
        /*->joinField('catalog_product_name',
            'catalog/category_product',
            'name',
            'product_id=entity_id',
            'category_id='.(int) $this->getRequest()->getParam('id', 0),
            'left');*/
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
//        if (!$this->getCompany()->getIsReadonly()) {
//            $this->addColumn('in_category', array(
//                'header_css_class' => 'a-center',
//                'type'      => 'checkbox',
//                'name'      => 'in_category',
//                'values'    => $this->_getSelectedProducts(),
//                'align'     => 'center',
//                'index'     => 'entity_id'
//            ));
//        }
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
            'align'     => 'right'
        ));
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
        return $this->getUrl('*/company_product/productEdit', array('id' => $row->getId()));
    }


    public function getGridUrl(){
        return $this->getUrl('*/company_company/productList', array('_current'=>true));
    }
}