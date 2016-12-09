<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 12:07 PM
 */
class Stableflow_Company_Model_Resource_Setup extends Mage_Eav_Model_Entity_Setup{

    public function getDefaultEntities(){
        $entities = array(
            'company_company' => array(
                'entity_model' => 'company/company',
                'attribute_model' => '',
                'table' => 'company/company_entity',
                'attributes' => array(
                    'name' => array(
                        'type' => 'varchar',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Name',
                        'input' => 'text',
                        'class' => '',
                        'source' => '',
                        'global' => 0,
                        'visible' => true,
                        'required' => true,
                        'user_defined' => true,
                        'default' => '',
                        'searchable' => true,
                        'filterable' => false,
                        'comparable' => false,
                        'visible_on_front' => true,
                        'unique' => false,
                    ),
                    'site' => array(
                        'type' => 'varchar',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Site Url',
                        'input' => 'text',
                        'class' => '',
                        'source' => '',
                        'global' => 0,
                        'visible' => true,
                        'required' => true,
                        'user_defined' => true,
                        'default' => '',
                        'searchable' => false,
                        'filterable' => false,
                        'comparable' => false,
                        'visible_on_front' => false,
                        'unique' => false,
                    ),
                    'description' => array(
                        'type' => 'text',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Description',
                        'input' => 'textarea',
                        'class' => '',
                        'source' => '',
                        'global' => 0,
                        'visible' => true,
                        'required' => true,
                        'user_defined' => true,
                        'default' => '',
                        'searchable' => false,
                        'filterable' => false,
                        'comparable' => false,
                        'visible_on_front' => false,
                        'unique' => false,
                    ),
                    'email'              => array(
                        'type'               => 'static',
                        'label'              => 'Email',
                        'input'              => 'text',
                        'sort_order'         => 80,
                        'validate_rules'     => 'a:1:{s:16:"input_validation";s:5:"email";}',
                        'position'           => 80,
                        'admin_checkout'    => 1
                    ),
                    'type_id'   =>  array(

                    ),
                    'customer_id'   =>  array(

                    ),
                ),
            ),
            'company_address' => array(
                'entity_model' => 'company/address',
                'attribute_model' => '',
                'table' => 'company/address_entity',
                'attributes' => array(
                    'street'             => array(
                        'type'               => 'text',
                        'label'              => 'Street Address',
                        'input'              => 'multiline',
                        'backend'            => 'customer/entity_address_attribute_backend_street',
                        'sort_order'         => 70,
                        'multiline_count'    => 2,
                        'validate_rules'     => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'           => 70,
                    ),
                    'city'               => array(
                        'type'               => 'varchar',
                        'label'              => 'City',
                        'input'              => 'text',
                        'sort_order'         => 80,
                        'validate_rules'     => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'           => 80,
                    ),
                    'country_id'         => array(
                        'type'               => 'varchar',
                        'label'              => 'Country',
                        'input'              => 'select',
                        'source'             => 'customer/entity_address_attribute_source_country',
                        'sort_order'         => 90,
                        'position'           => 90,
                    ),
                    'email'              => array(
                        'type'               => 'static',
                        'label'              => 'Email',
                        'input'              => 'text',
                        'sort_order'         => 80,
                        'validate_rules'     => 'a:1:{s:16:"input_validation";s:5:"email";}',
                        'position'           => 80,
                        'admin_checkout'    => 1
                    ),
                    'postcode'           => array(
                        'type'               => 'varchar',
                        'label'              => 'Zip/Postal Code',
                        'input'              => 'text',
                        'sort_order'         => 110,
                        'validate_rules'     => 'a:0:{}',
                        'data'               => 'customer/attribute_data_postcode',
                        'position'           => 110,
                    ),
                    'telephone'          => array(
                        'type'               => 'varchar',
                        'label'              => 'Telephone',
                        'input'              => 'text',
                        'sort_order'         => 120,
                        'validate_rules'     => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'           => 120,
                    ),
                    'fax'                => array(
                        'type'               => 'varchar',
                        'label'              => 'Fax',
                        'input'              => 'text',
                        'required'           => false,
                        'sort_order'         => 130,
                        'validate_rules'     => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'           => 130,
                    ),
                ),
            ),
            'company_prices' => array(
                'entity_model' => 'company/prices',
                'attribute_model' => '',
                'table' => 'company/prices_entity',
                'attributes' => array(
                    'name' => array(
                        'type' => 'varchar',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Name',
                        'input' => 'text',
                        'class' => '',
                        'source' => '',
                        'global' => 0,
                        'visible' => true,
                        'required' => true,
                        'user_defined' => true,
                        'default' => '',
                        'searchable' => true,
                        'filterable' => false,
                        'comparable' => false,
                        'visible_on_front' => true,
                        'unique' => false,
                    ),

                ),
            ),
        );
        return $entities;
    }
}