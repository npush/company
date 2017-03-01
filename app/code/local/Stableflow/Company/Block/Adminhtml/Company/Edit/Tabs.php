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
        $this->setTitle(Mage::helper('company')->__('Company Information'));
    }


    protected function _prepareLayout()
    {
        $company = $this->getCompany();
        $entity = Mage::getModel('eav/entity_type')
            ->load('company_company', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entity->getEntityTypeId());

        //$attributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'general',
            array(
                'label'   => Mage::helper('company')->__('General Information'),
                'content' => $this->getLayout()->createBlock(
                    'company/adminhtml_company_edit_tab_general'
                )
                    ->setAttributes($attributes)
                    ->toHtml(),
            )
        );
/*        $this->addTab(
            'address',
            array(
                'label'   => Mage::helper('company')->__('Company Address'),
                'content' => $this->getLayout()->createBlock(
                    'company/adminhtml_company_edit_tab_address'
                )
                ->initForm()
                ->toHtml(),
            )
        );*/
        $this->addTab(
            'owner',
            array(
                'label'   => Mage::helper('company')->__('Company Owners'),
                'content' => $this->getLayout()->createBlock(
                    'company/adminhtml_company_edit_tab_owner'
                )
                //->setAttributes($attributes)
                ->toHtml(),
            )
        );
        $this->addTab(
            'price',
            array(
                'label'   => Mage::helper('company')->__('Company Prices'),
                'content' => $this->getLayout()->createBlock(
                    'company/adminhtml_company_edit_tab_price'
                )
                //->setAttributes($attributes)
                ->toHtml(),
            )
        );
        $this->addTab(
            'products',
            array(
                'label'   => Mage::helper('company')->__('Company Products List'),
                'content' => $this->getLayout()->createBlock(
                    'company/adminhtml_company_edit_tab_products'
                )
                    //->setAttributes($attributes)
                    ->toHtml(),
            )
        );


        return parent::_beforeToHtml();
    }

    public function getCompany()
    {
        return Mage::registry('current_company');
    }
}