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

        $this->setId('company_products_grid');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        //$this->setTemplate('company/tab/products.phtml');
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
        return Mage::registry('current_company');
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
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('price')
            //->addStoreFilter($this->getRequest()->getParam('store'))
            /*->joinField('position',
                'catalog/category_product',
                'position',
                'product_id=entity_id',
                'category_id='.(int) $this->getRequest()->getParam('id', 0),
                'left')*/;
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
        if (!$this->getCompany()->getIsReadonly()) {
            $this->addColumn('in_category', array(
                'header_css_class' => 'a-center',
                'type'      => 'checkbox',
                'name'      => 'in_category',
                'values'    => $this->_getSelectedProducts(),
                'align'     => 'center',
                'index'     => 'entity_id'
            ));
        }
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('catalog')->__('ID'),
            'sortable'  => true,
            'width'     => '60',
            'index'     => 'entity_id'
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('catalog')->__('Name'),
            'index'     => 'name'
        ));
        $this->addColumn('sku', array(
            'header'    => Mage::helper('catalog')->__('SKU'),
            'width'     => '80',
            'index'     => 'sku'
        ));
        $this->addColumn('price', array(
            'header'    => Mage::helper('catalog')->__('Price'),
            'type'  => 'currency',
            'width'     => '1',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'     => 'price'
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
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