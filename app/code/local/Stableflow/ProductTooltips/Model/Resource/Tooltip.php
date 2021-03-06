<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/8/17
 * Time: 3:22 PM
 */
class Stableflow_ProductTooltips_Model_Resource_Tooltip extends Mage_Core_Model_Resource_Db_Abstract{

    protected $_valueTable;

    /**
     * Main table primary key field name
     *
     * @var string
     */
    protected $_idFieldName = 'tooltip_id';


    /**
     *
     */
    public function _construct()
    {
        $this->_init('product_tooltips/tooltip', 'tooltip_id');
        $this->_valueTable = $this->getTable('product_tooltips/tooltip_value');
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Mage_Rating_Model_Rating $object
     * @return Varien_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object){
        $select = parent::_getLoadSelect($field, $value, $object);

        $select->joinLeft(
            array('value_table' => $this->_valueTable),
            $this->getMainTable(). '.tooltip_id = value_table.tooltip_id AND '. Mage::app()->getStore(true)->getId() . ' = value_table.store_id',
            array('title','description')
        );
        return $select;

//        $adapter    = $this->_getReadAdapter();
//
//        $table      = $this->getMainTable();
//        $storeId    = (int)Mage::app()->getStore()->getId();
//        $select     = parent::_getLoadSelect($field, $value, $object);
//        $codeExpr   = $adapter->getIfNullSql('title.value', "{$table}.rating_code");
//
//        $select->joinLeft(
//            array('title' => $this->getTable('rating/rating_title')),
//            $adapter->quoteInto("{$table}.rating_id = title.rating_id AND title.store_id = ?", $storeId),
//            array('rating_code' => $codeExpr));
//
//        return $select;
    }

    /**
     * Actions after load
     *
     * @param Mage_Rating_Model_Rating $object
     * @return Mage_Rating_Model_Resource_Rating
     */
    public function afterLoad(Mage_Core_Model_Abstract $object)
    {
        parent::afterLoad($object);

//        if (!$object->getId()) {
//            return $this;
//        }
//
//        $adapter = $this->_getReadAdapter();
//        $bind    = array(':tooltip_id' => (int)$object->getId());
//        // load rating titles
//        $select  = $adapter->select()
//            ->from($this->_valueTable, array('store_id', 'value'))
//            ->where('tooltip_id=:tooltip_id');
//
//        $result  = $adapter->fetchPairs($select, $bind);
//        if ($result) {
//            $object->setRatingCodes($result);
//        }
//
//        // load rating available in stores
//        $object->setStores($this->getStores((int)$object->getId()));
//
//        return $this;
    }

    /**
     * Actions after save
     *
     * @param Mage_Rating_Model_Rating $object
     * @return Mage_Rating_Model_Resource_Rating
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        parent::_afterSave($object);

        $adapter = $this->_getWriteAdapter();
        $tooltipId = (int)$object->getId();

        $adapter->beginTransaction();
        try {
            $select = $adapter->select()
                ->from($this->_valueTable, array('store_id', 'description', 'title'))
                ->where('tooltip_id = :tooltip_id');
            $old = $adapter->fetchRow($select, array(':tooltip_id' => $tooltipId));
            $new = array(
                'store_id' => 1,
                'description' => $object->getDescription(),
                'title' => $object->getTitle()
            );

            $insert = array_diff_assoc($new, $old);
            $delete = array_diff_assoc($old, $new);
            if (!empty($delete)) {
                $where = array(
                    'tooltip_id = ?' => $tooltipId,
                    'store_id IN(?)' => array_keys($delete)
                );
                $adapter->delete($this->_valueTable, $where);
            }

            if ($insert) {
                $data = array();
                foreach ($insert as $storeId => $title) {
                    $data[] = array(
                        'tooltip_id' => $tooltipId,
                        'store_id' => (int)$storeId,
                        'value' => $title
                    );
                }
                if (!empty($data)) {
                    $adapter->insertMultiple($this->_valueTable, $data);
                }
            }
            $adapter->commit();
        } catch (Exception $e) {
            Mage::logException($e);
            $adapter->rollBack();
        }
    }
}