<?php

/**
 * Created by PhpStorm.
 * User: nick
 * Date: 12/10/16
 * Time: 1:51 PM
 */
class  Stableflow_Company_Block_Adminhtml_Company_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs{

    public function __construct()
    {
        parent::__construct();
        $this->setId('company_info_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('mageplaza_betterblog')->__('Company Information'));
    }


    protected function _prepareLayout()
    {
        $company = $this->getCompany();
        $entity = Mage::getModel('eav/entity_type')
            ->load('company', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entity->getEntityTypeId());
        $attributes->addFieldToFilter(
            'attribute_code',
            array(
                'nin' => array('meta_title', 'meta_description', 'meta_keywords')
            )
        );
        $attributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'info',
            array(
                'label'   => Mage::helper('mageplaza_betterblog')->__('Post Information'),
                'content' => $this->getLayout()->createBlock(
                    'mageplaza_betterblog/adminhtml_post_edit_tab_attributes'
                )
                    ->setAttributes($attributes)
                    ->toHtml(),
            )
        );
        $seoAttributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entity->getEntityTypeId())
            ->addFieldToFilter(
                'attribute_code',
                array(
                    'in' => array('meta_title', 'meta_description', 'meta_keywords')
                )
            );
        $seoAttributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'meta',
            array(
                'label'   => Mage::helper('mageplaza_betterblog')->__('Meta'),
                'title'   => Mage::helper('mageplaza_betterblog')->__('Meta'),
                'content' => $this->getLayout()->createBlock(
                    'mageplaza_betterblog/adminhtml_post_edit_tab_attributes'
                )
                    ->setAttributes($seoAttributes)
                    ->toHtml(),
            )
        );
        $this->addTab(
            'categories',
            array(
                'label' => Mage::helper('mageplaza_betterblog')->__('Categories'),
                'url'   => $this->getUrl('*/*/categories', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        $this->addTab(
            'tags',
            array(
                'label' => Mage::helper('mageplaza_betterblog')->__('Tags'),
                'url'   => $this->getUrl('*/*/tags', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    public function getPost()
    {
        return Mage::registry('current_post');
    }
}