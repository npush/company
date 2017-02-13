<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/10/17
 * Time: 3:33 PM
 */
class Stableflow_ProductTooltips_Model_Resource_Value_Value extends Mage_Core_Model_Resource_Db_Abstract{

    /**
     *
     */
    public function _construct(){
        $this->_init('product_tooltips/tooltip_value', 'id');
    }

    /**
     * Save attachment - product relations
     *
     * @access public
     * @param Web4pro_Attachments_Model_Attachment $attachment
     * @param array $data
     * @return Web4pro_Attachments_Model_Resource_Attachment_Product
     * @author WEB4PRO <srepin@corp.web4pro.com.ua>
     */
    public function saveAttachmentRelation($attachment, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('attachment_id=?', $attachment->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $productId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'attachment_id' => $attachment->getId(),
                    'product_id'    => $productId,
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }

    /**
     * Save  product - attachment relations
     *
     * @access public
     * @param Mage_Catalog_Model_Product $prooduct
     * @param array $data
     * @return Web4pro_Attachments_Model_Resource_Attachment_Product
     * @@author WEB4PRO <srepin@corp.web4pro.com.ua>
     */
    public function saveProductRelation($product, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('product_id=?', $product->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $attachmentId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'attachment_id' => $attachmentId,
                    'product_id'    => $product->getId(),
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }
}