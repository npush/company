<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/13/16
 * Time: 5:53 PM
 */
class Mageplaza_BetterBlog_Model_Resource_Post_Relation extends Mage_Core_Model_Resource_Db_Abstract {

    /**
     * Initialize resource model and define main table
     *
     */
    protected function _construct()
    {
        $this->_init('mageplaza_betterblog/post_product_category', 'rel_id');
    }

    public function savePostRelation($postId, $data)
    {
        try {
            if (!is_array($data)) {
                $data = array();
            }

            $adapter = $this->_getWriteAdapter();
            $bind = array(
                ':post_id' => $postId,
            );
            $select = $adapter->select()
                ->from($this->getMainTable(), array('rel_id', 'category_id'))
                ->where('post_id = :post_id');

            $related = $adapter->fetchPairs($select, $bind);
            $deleteIds = array();

            foreach ($related as $relId => $categoryId) {
                if (!isset($data[$categoryId])) {
                    $deleteIds[] = (int)$relId;
                }
            }
            if (!empty($deleteIds)) {
                $adapter->delete(
                    $this->getMainTable(),
                    array('rel_id IN (?)' => $deleteIds)
                );
            }

            foreach ($data as $categoryId) {
                $adapter->insertOnDuplicate(
                    $this->getMainTable(),
                    array(
                        'post_id' => $postId,
                        'category_id' => $categoryId,
                    )
                );
            }
        }catch (Exception $e){
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function getRelatedPostIds($categoryId){
        $adapter = $this->_getReadAdapter();
        $bind    = array(
            ':category_id'    => $categoryId,
        );
        $select = $adapter->select()
            ->from($this->getMainTable(), array('rel_id', 'post_id'))
            ->where('category_id = :category_id');
        $related   = $adapter->fetchPairs($select, $bind);
        return $related;
    }

}