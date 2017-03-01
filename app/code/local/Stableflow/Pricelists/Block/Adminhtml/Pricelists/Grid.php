<?php
class Stableflow_Pricelists_Block_Adminhtml_Pricelists_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
 
    protected function _construct()
    {
        $this->setId('pricelistsGrid');
        $this->_controller = 'adminhtml_pricelists';
        $this->setUseAjax(true);
        
        $this->setDefaultSort('id');
        $this->setDefaultDir('desc');
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('pricelists/pricelist')->getCollection();
        $this->setCollection($collection);
 
        return parent::_prepareCollection();
    }
 
    protected function _prepareColumns()
    {

        $this->addColumn('entity_id', array(
            'header'        => Mage::helper('stableflow_pricelists')->__('ID'),
            'align'         => 'left',
            'filter_index'  => 'id',
            'index'         => 'id',
            'type'          => 'text',
            'truncate'      => 50,
            'escape'        => true,
            'width'         => '25px'
        ));

        $this->addColumn('id_company', array(
            'header'        => Mage::helper('stableflow_pricelists')->__('Company Name'),
            'align'         => 'left',
            'filter_index'  => 'id_company',
            'index'         => 'id_company',
            'type'          => 'text',
            'truncate'      => 50,
            'escape'        => true,
            'width'         => '350px'
        ));


        $this->addColumn('status', array(
            'header'        => Mage::helper('stableflow_pricelists')->__('Status'),
            'align'         => 'left',
            'filter_index'  => 'status',
            'index'         => 'status',
            'width'         => '100px',
            'type'          => 'options',
            'options'       => Mage::getModel('pricelists/resource_pricelist')->toArray()
        ));

        $this->addColumn('schedule_at', array(
            'header'        => Mage::helper('stableflow_pricelists')->__('Schedule At'),
            'align'         => 'left',
            'filter_index'  => 'date',
            'index'         => 'date',
            'type'          => 'date',
            'width'         => '70',
            'truncate'      => 50,
            'escape'        => true,
        ));

        $this->addColumn('created_at', array(
            'header'        => Mage::helper('stableflow_pricelists')->__('Created At'),
            'align'         => 'left',
            'filter_index'  => 'date',
            'index'         => 'date',
            'type'          => 'date',
            'width'         => '70px',
            'truncate'      => 50,
            'escape'        => true,
        ));

        $this->addColumn('updated_at', array(
            'header'        => Mage::helper('stableflow_pricelists')->__('Updated At'),
            'align'         => 'left',
            'filter_index'  => 'date',
            'index'         => 'date',
            'type'          => 'date',
            'width'         => '70px',
            'truncate'      => 50,
            'escape'        => true,
        ));
 
        return parent::_prepareColumns();
    }

    public function getRowUrl($pricelists)
    {
        return $this->getUrl('*/*/edit', array(
            'id' => $pricelists->getId(),
        ));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}
