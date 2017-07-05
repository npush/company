<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/4/17
 * Time: 5:04 PM
 */
class Stableflow_BlackIp_Block_Adminhtml_Blacklist_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('blacklistGrid');
        // This is the primary key of the database
        $this->setDefaultSort('url_c');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);

        $this->_headerText = Mage::helper('sf_blackip')->__('Ip logs');

    }
    protected function _prepareCollection(){


        $model = Mage::getModel('sf_blackip/blacklist');
        $collection = $model->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();

        $rs = Mage::getSingleton('core/resource');

        $visitor = $rs->getTableName('log_visitor');
        $visitor_online = $rs->getTableName('log_visitor_online');
        $visitor_info = $rs->getTableName('log_visitor_info');

        $log_url = $rs->getTableName('log_url');
        $log_url_info = $rs->getTableName('log_url_info');
        $ip_notes = $rs->getTableName('log_remoteaddr_notes');

        $select = $collection->getSelect();
        $select->reset('columns');
        $select->reset('from');

        $select->from(array('main_table'=>$visitor), array('main_table.visitor_id', 'MAX(main_table.last_visit_at) AS last_visit_time'));

        $select->joinLeft(array('vi'=>$visitor_info), 'vi.visitor_id=main_table.visitor_id', 'vi.remote_addr');
        $select->joinLeft(array('lu'=>$log_url), 'lu.visitor_id=main_table.visitor_id', array('url_c'=>'COUNT(*)'));
        //$select->joinLeft(array('lui'=>$log_url_info), 'lui.url_id=last_url_id', array('last_url'=>'lui.url'));
        $select->joinLeft(array('lrn'=>$ip_notes), 'lrn.remote_addr=vi.remote_addr', array('blocked','white','note','watch'));

        $select->group('vi.remote_addr');
        $select->having('lrn.blocked=0 OR lrn.blocked IS NULL');
        $select->having('lrn.white=0 OR lrn.white IS NULL');
        $select->having('lrn.watch=0 OR lrn.watch IS NULL');

        $this->setCollection($collection);

        //$collection->printLogQuery(true);

        //die();

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('black_ip', array(
            'header'    => Mage::helper('sf_blackip')->__('IP address'),
            'index'     => 'black_ip',
            'width'     => '80px',
            'align'		=> 'center',
            //'renderer'  => 'sf_blackip/adminhtml_blackip_renderer_google'
        ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('customer')->__('View'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'    => 'getRemoteAddr',
                'align'		=> 'center',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('customer')->__('View'),
                        'url'       => array('base'=> '*/*/logurl'),
                        'field'     => 'remote_addr'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'view',
                'is_system' => true,
            ));
        $this->addColumn('comment', array(

            'header'    => Mage::helper('sf_blackip')->__('Notes'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'comment',
            'type'      => 'text',
            //'renderer'  => 'sf_blackip/adminhtml_blackip_renderer_notes'
        ));



        $this->addColumn('creation_time', array(
            'header'    => Mage::helper('sf_blackip')->__('Added at'),
            'align'     => 'center',
            'width'     => '120px',
            'type'      => 'datetime',
            'default'   => '--',
            'index'     => 'creation_time',
            'sortable'  => true
        ));
        /*
        $this->addColumn('last_url', array(
                'header'    => Mage::helper('visitoripsecurity')->__('Last url'),
                'align'     => 'center',
                'width'     => '120px',
                'type'      => 'text',
                'default'   => '--',
                'index'     => 'last_url',
                'filter'    => false,
                'sortable'  => false,
                'renderer'  => 'visitoripsecurity/adminhtml_visitoripsecurity_renderer_lasturl'
        ));
        */
        //$this->print_a($this);
        //var_dump($this);

        return parent::_prepareColumns();
    }
    protected function _addColumnFilterToCollection($column)
    {
        $rs = Mage::getSingleton('core/resource');

        $visitor_online = $rs->getTableName('log_visitor_online');
        $visitor_info = $rs->getTableName('log_visitor_info');
        $log_url = $rs->getTableName('log_url');
        $log_url_info = $rs->getTableName('log_url_info');
        $ip_notes = $rs->getTableName('log_remoteaddr_notes');

        if ($this->getCollection()) {
            if ($column->getId() == 'remote_addr') {

                $cond = $column->getFilter()->getCondition();
                if(!empty($cond)){
                    $field = new Zend_Db_Expr('INET_NTOA(vi.remote_addr)');
                    $this->getCollection()->addFieldToFilter($field , $cond);
                }

                return $this;
            }elseif ($column->getId() == 'last_visit_at') {

                $cond = $column->getFilter()->getCondition();
                if(!empty($cond)){
                    $field = 'main_table.last_visit_at';
                    $this->getCollection()->addFieldToFilter($field , $cond);
                }

                return $this;
            }elseif ($column->getId() == 'first_visit_at') {

                $cond = $column->getFilter()->getCondition();


                if(!empty($cond)){
                    $field = 'main_table.first_visit_at';
                    $this->getCollection()->addFieldToFilter($field , $cond);
                }

                return $this;
            }elseif ($column->getId() == 'note') {

                $cond = $column->getFilter()->getCondition();
                if(!empty($cond)){
                    $field = new Zend_Db_Expr('lrn.note');
                    $this->getCollection()->addFieldToFilter($field , $cond);
                }
                return $this;

            }else{
                return parent::_addColumnFilterToCollection($column);
            }
        }
    }
    public function getRowUrl($row)
    {
        //return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/index', array('_current'=>true));
    }
}