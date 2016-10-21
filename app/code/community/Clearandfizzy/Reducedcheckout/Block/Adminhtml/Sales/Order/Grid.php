<?php

class Clearandfizzy_Reducedcheckout_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{

    /**
     * Prepare grid columns
     *
     * @return Clearandfizzy_Reducedcheckout_Block_Adminhtml_Sales_Order_Grid
     */
    protected function _prepareColumns(){
        parent::_prepareColumns();

        // Add order comment to grid
        $this->addColumn('order_comment', array(
            'header'    => Mage::helper('clearandfizzy_reducedcheckout')->__('Order Comment'),
            'index'     => 'order_comment',
            'type'      => 'text'
        ));
        return $this;
    }

    protected function _prepareCollection(){
        /*$collection = Mage::getResourceModel($this->_getCollectionClass());
        $collection->getSelect()->join('sales_flat_order', 'main_table.entity_id = sales_flat_order.parent_id', array('order_comment'));
        //$collection->addFieldToFilter('sales_flat_order_address.address_type', array('eq' => 'billing'));
        $this->setCollection($collection);*/
        return parent::_prepareCollection();
    }
}
