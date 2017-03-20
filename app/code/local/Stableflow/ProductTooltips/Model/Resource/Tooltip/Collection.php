<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/9/17
 * Time: 2:43 PM
 */
class Stableflow_ProductTooltips_Model_Resource_Tooltip_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{

    protected $_joinedFields = array();

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('product_tooltips/tooltip');
    }

    /**
     * Init collection select
     *
     * @return unknown
     */
/*    public function _initSelect(){
        parent::_initSelect();
        $this->getSelect()
            ->joinLeft(
                array('product_relation' => $this->getTable('product_tooltip')),
                'main_table.tooltip_id = product_relation.tooltip_id',
                array('code')
            );
        return $this;
    }*/


    /**
     * get tooltips as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     */
/*    protected function _toOptionArray($valueField='id', $labelField='label', $additional=array())
    {
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }*/


    /**
     * add the product filter to collection
     *
     * @access public
     * @param mixed (Mage_Catalog_Model_Product|int) $product
     * @return Stableflow_ProductTooltips_Model_Resource_Tooltip_Collection
     */
/*    public function addProductFilter($product)
    {
        if ($product instanceof Mage_Catalog_Model_Product) {
            $product = $product->getId();
        }
        if (!isset($this->_joinedFields['product'])) {
            $this->getSelect()->join(
                array('related_product' => $this->getTable('product_tooltips/tooltip_product')),
                'related_product.tooltip_id = main_table.id',
                array('position')
            );
            $this->getSelect()->where('related_product.product_id = ?', $product);
            $this->_joinedFields['product'] = true;
        }
        return $this;
    }*/

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @access public
     * @return Varien_Db_Select
     */
/*    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }*/
}