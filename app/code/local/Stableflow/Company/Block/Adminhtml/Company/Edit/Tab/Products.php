<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 2/21/17
 * Time: 5:13 PM
 */

class Stableflow_Company_Block_Adminhtml_Company_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Grid{

    /**
     * 
     */
    public function __construct(){
        parent::__construct();
        $this->setSaveParametersInSession(false);
        $this->setId('company_products');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        //$this->setTemplate('company/tab/products.phtml');
    }

    protected function _prepareLayout(){
        $this->setChild('add_new_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('company')->__('Add new product'),
                    'onclick'   => $this->getJsObjectName().'.doAddNew()',
                    'class'   => 'task'
                ))
        );
        parent::_prepareLayout();
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
     * Initialize form object
     *
     * @return Mage_Adminhtml_Block_Customer_Edit_Tab_Addresses
     */
    public function initForm(){
    }

    /**
     * get current entity
     *
     */
    public function getCompany(){
        $id = Mage::getSingleton('adminhtml/session')->getCompanyId();
        return Mage::getModel('company/company')->load($id);
    }

    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in category flag
        if ($column->getId() == 'in_category') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
            }
            elseif(!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$productIds));
            }
        }
        else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
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

    public function getGridUrl()
    {
        return $this->getUrl('*/*/productListGrid', array('_current'=>true));
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

}