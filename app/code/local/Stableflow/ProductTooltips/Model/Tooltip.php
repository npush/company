<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/8/17
 * Time: 3:20 PM
 */

class Stableflow_ProductTooltips_Model_Tooltip extends Mage_Core_Model_Abstract{

    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'product_tooltips_tooltip';
    const CACHE_TAG = 'product_tooltips_tooltip';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'product_tooltips_tooltip';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'tooltip';
    protected $_productInstance = null;


    /**
     * constructor
     *
     */
    public function _construct(){
        $this->_init('product_tooltips/tooltip');
    }

    /**
     * before save attachment
     *
     * @return Stableflow_ProductTooltips_Model_Tooltip
     */
/*    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }*/

    /**
     * save attachment relation
     *
     * @return Stableflow_ProductTooltips_Model_Tooltip
     */
/*    protected function _afterSave()
    {
        $this->getProductInstance()->saveAttachmentRelation($this);
        return parent::_afterSave();
    }*/
}