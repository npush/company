<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 12:07 PM
 */

class Stableflow_Company_Model_Resource_Setup extends Mage_Eav_Model_Entity_Setup{

    protected function _prepareValues($attr){
        $data = parent::_prepareValues($attr);
        $data = array_merge($data, array(
            'is_visible'                => $this->_getValue($attr, 'visible_on_front', 1),
            'input_filter'              => $this->_getValue($attr, 'input_filter', null),
            'validate_rules'            => $this->_getValue($attr, 'validate_rules', null),
            'data_model'                => $this->_getValue($attr, 'data', null),
            'is_system'                 => $this->_getValue($attr, 'system', 1),
            'multiline_count'           => $this->_getValue($attr, 'multiline_count', 0),
            'sort_order'                => $this->_getValue($attr, 'position', 0)
        ));
        return $data;
    }

    public function getDefaultEntities(){
        $entities = array(
            'company_company' => array(
                'entity_model'                  => 'company/company',
                'attribute_model'               => 'company/attribute',
                'table'                         => 'company/company_entity',
                'additional_attribute_table'    => 'company/eav_attribute',
                'entity_attribute_collection'   => 'company/company_attribute_collection',
                'attributes' => array(
                    'image' => array(
                        'type'              => 'varchar',
                        'label'             => 'Company Logo',
                        'input'             => 'image',
                        'backend'           => 'company/company_attribute_backend_image',
                        'required'          => false,
                        'visible'           => true,
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'sort_order'        => 10,
                        'position'          => 10,
                    ),
                    'name' => array(
                        'type'              => 'varchar',
                        'backend'           => '',
                        'frontend'          => '',
                        'label'             => 'Name',
                        'input'             => 'text',
                        'source'            => '',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
                        'required'          => true,
                        'user_defined'      => true,
                        'default'           => '',
                        'unique'            => true,
                        'searchable'        => true,
                        'visible_on_front'  => true,
                        'sort_order'        => 20,
                        'position'          => 20,
                    ),
                    'status' => array(
                        'type'              => 'static',
                        'label'             => 'Status',
                        'input'             => 'select',
                        'source'            => 'company/company_attribute_source_status',
                        'default'           => '2',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'sort_order'        => 30,
                        'position'          => 30,
                    ),
                    'type' =>  array(
                        'type'              => 'int',
                        'label'             => 'Company Type',
                        'input'             => 'select',
                        'source'            => 'company/company_attribute_source_type',
                        'backend'           => '',
                        'sort_order'        => 40,
                        'position'          => 40,
                    ),
                    'url' => array(
                        'type'              => 'varchar',
                        'backend'           => '',
                        'frontend'          => '',
                        'label'             => 'Site Url',
                        'input'             => 'text',
                        'source'            => '',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
                        'required'          => false,
                        'user_defined'      => true,
                        'default'           => '',
                        'unique'            => false,
                        'searchable'        => false,
                        'visible_on_front'  => true,
                        'sort_order'        => 50,
                        'position'          => 50,
                    ),
                    'short_description' => array(
                        'type'              => 'text',
                        'backend'           => '',
                        'frontend'          => '',
                        'label'             => 'Sort Description',
                        'input'             => 'textarea',
                        'source'            => '',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'          => false,
                        'user_defined'      => true,
                        'default'           => '',
                        'unique'            => false,
                        'searchable'        => false,
                        'visible_on_front'  => true,
                        'sort_order'        => 60,
                        'position'          => 60,
                    ),
                    'description' => array(
                        'type'              => 'text',
                        'backend'           => '',
                        'frontend'          => '',
                        'label'             => 'Description',
                        'input'             => 'textarea',
                        'source'            => '',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'          => false,
                        'user_defined'      => true,
                        'default'           => '',
                        'unique'            => false,
                        'searchable'        => false,
                        'visible_on_front'  => true,
                        'sort_order'        => 70,
                        'position'          => 70,
                    ),
                    'customer_id' =>  array(
                        'type'              => 'int',
                        'label'             => 'Associate to Customer',
                        'input'             => 'select',
                        'source'            => 'company/company_attribute_source_customer',
                        'backend'           => 'company/company_attribute_backend_customer',
                        'sort_order'        => 90,
                        'position'          => 90,
                    ),
                    'activity' => array(
                        'type'              => 'int',
                        'label'             => 'Company Activity',
                        'input'             => 'multiselect',
                        'source'            => 'company/company_attribute_source_activity',
                        'backend'           => '',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'sort_order'        => 100,
                        'position'          => 100,
                    ),
                    'company_code' => array(
                        'type'              => 'varchar',
                        'backend'           => '',
                        'frontend'          => '',
                        'label'             => 'Company Code',
                        'input'             => 'text',
                        'source'            => '',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'          => false,
                        'user_defined'      => true,
                        'default'           => '',
                        'visible_on_front'  => false,
                        'unique'            => true,
                    ),
                    'address_id'    => array(
                        'type'              => 'int',
                        'label'             => 'Address',
                        'input'             => 'text',
                        'source'            => 'company/company_attribute_source_address',
                        'backend'           => 'company/company_attribute_backend_address',
                        'required'          => false,
                        'visible'           => false,
                    ),
                    'price_id'    => array(
                        'type'              => 'int',
                        'label'             => 'Price Model',
                        'input'             => 'text',
                        'source'            => 'company/company_attribute_source_price',
                        'backend'           => 'company/company_attribute_backend_price',
                        'required'          => false,
                        'visible'           => false,
                    ),
                ),
            ),
            'company_address' => array(
                'entity_model'                  => 'company/address',
                'attribute_model'               => 'company/attribute',
                'table'                         => 'company/address_entity',
                'additional_attribute_table'    => 'company/eav_attribute',
                'entity_attribute_collection'   => 'company/address_attribute_collection',
                'attributes' => array(
                    'street'             => array(
                        'type'              => 'text',
                        'label'             => 'Street Address',
                        'input'             => 'multiline',
                        'backend'           => 'company/entity_address_attribute_backend_street',
                        'sort_order'        => 70,
                        'multiline_count'   => 2,
                        'validate_rules'    => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'          => 70,
                    ),
                    'city'               => array(
                        'type'              => 'varchar',
                        'label'             => 'City',
                        'input'             => 'text',
                        'sort_order'        => 80,
                        'validate_rules'    => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'          => 80,
                    ),
                    'country_id'         => array(
                        'type'              => 'varchar',
                        'label'             => 'Country',
                        'input'             => 'select',
                        'source'            => 'company/entity_address_attribute_source_country',
                        'sort_order'        => 90,
                        'position'          => 90,
                    ),
                    'email'              => array(
                        'type'              => 'varchar',
                        'label'             => 'Email',
                        'input'             => 'text',
                        'sort_order'        => 80,
                        'validate_rules'    => 'a:1:{s:16:"input_validation";s:5:"email";}',
                        'position'          => 80,
                    ),
                    'postcode'           => array(
                        'type'              => 'varchar',
                        'label'             => 'Zip/Postal Code',
                        'input'             => 'text',
                        'sort_order'        => 110,
                        'validate_rules'    => 'a:0:{}',
                        'data'              => 'company/attribute_data_postcode',
                        'position'          => 110,
                    ),
                    'telephone'          => array(
                        'type'              => 'varchar',
                        'label'             => 'Telephone',
                        'input'             => 'text',
                        'sort_order'        => 120,
                        'validate_rules'    => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'          => 120,
                    ),
                    'fax'                => array(
                        'type'              => 'varchar',
                        'label'             => 'Fax',
                        'input'             => 'text',
                        'required'          => false,
                        'sort_order'        => 130,
                        'validate_rules'    => 'a:2:{s:15:"max_text_length";i:255;s:15:"min_text_length";i:1;}',
                        'position'          => 130,
                    ),
                ),
            ),
            'company_product' => array(
                'entity_model'                  => 'company/product',
                'attribute_model'               => 'company/attribute',
                'table'                         => 'company/product_entity',
                'additional_attribute_table'    => 'company/eav_attribute',
                'entity_attribute_collection'   => 'company/product_attribute_collection',
                'attributes' => array(
                    'name' => array(
                        'type'              => 'varchar',
                        'backend'           => '',
                        'frontend'          => '',
                        'label'             => 'Name',
                        'input'             => 'text',
                        'source'            => '',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
                        'required'          => true,
                        'user_defined'      => true,
                        'default'           => '',
                        'unique'            => false,
                        'searchable'        => true,
                        'visible_on_front'  => true,
                    ),
                    'manufacturer_code' => array(
                        'type'              => 'varchar',
                        'backend'           => '',
                        'frontend'          => '',
                        'label'             => 'Manufacturer Code',
                        'input'             => 'text',
                        'source'            => '',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
                        'required'          => false,
                        'user_defined'      => true,
                        'default'           => '',
                        'unique'            => false,
                        'searchable'        => true,
                        'visible_on_front'  => true,
                    ),
                    'additional_code' => array(
                        'type'              => 'varchar',
                        'backend'           => '',
                        'frontend'          => '',
                        'label'             => 'Additional Code',
                        'input'             => 'text',
                        'source'            => '',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
                        'required'          => false,
                        'user_defined'      => true,
                        'default'           => '',
                        'unique'            => false,
                        'searchable'        => true,
                        'visible_on_front'  => true,
                    ),
                    'remark' => array(
                        'type'              => 'varchar',
                        'backend'           => '',
                        'frontend'          => '',
                        'label'             => 'Remark',
                        'input'             => 'textarea',
                        'source'            => '',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
                        'required'          => false,
                        'user_defined'      => true,
                        'default'           => '',
                        'unique'            => false,
                        'searchable'        => false,
                        'visible_on_front'  => false,
                    ),
                    'price' => array(
                        'type'              => 'decimal',
                        'label'             => 'Price',
                        'input'             => 'price',
                        'backend'           => 'company/product_attribute_backend_price',
                        'sort_order'        => 1,
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
                    ),
                    'price_int' => array(
                        'type'              => 'decimal',
                        'label'             => 'Price Internal',
                        'input'             => 'price',
                        'backend'           => 'company/product_attribute_backend_price',
                        'sort_order'        => 1,
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
                    ),
                    'price_wholesale' => array(
                        'type'              => 'decimal',
                        'label'             => 'Price Wholesale',
                        'input'             => 'price',
                        'backend'           => 'company/product_attribute_backend_price',
                        'sort_order'        => 1,
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
                    ),
                    'measure'  => array(
                        'type'              => 'varchar',
                        'backend'           => '',
                        'frontend'          => '',
                        'label'             => 'Measure Unit',
                        'input'             => 'text',
                        'source'            => '',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
                        'required'          => false,
                        'user_defined'      => true,
                        'default'           => '',
                        'unique'            => false,
                        'searchable'        => false,
                        'visible_on_front'  => false,
                    ),
                    'qty'   => array(
                        'type'              => 'int',
                        'backend'           => '',
                        'frontend'          => '',
                        'label'             => 'Available QTY',
                        'input'             => 'text',
                        'source'            => '',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
                        'required'          => false,
                        'user_defined'      => true,
                        'default'           => '',
                        'unique'            => false,
                        'searchable'        => false,
                        'visible_on_front'  => false,
                    ),
                ),
            ),
            /*'company_prices' => array(
                'entity_model'                  => 'company/prices',
                'attribute_model'               => 'company/attribute',
                'table'                         => 'company/prices_entity',
                'additional_attribute_table'    => 'company/eav_attribute',
                'entity_attribute_collection'   => 'company/attribute_collection',
                'attributes' => array(
                    'name' => array(
                        'type'              => 'varchar',
                        'backend'           => '',
                        'frontend'          => '',
                        'label'             => 'Name',
                        'input'             => 'text',
                    ),
                    'price' => array(

                    ),
                    'price_int' => array(

                    ),
                    'price_wholesale' => array(

                    ),
                ),
            ),*/
        );
        return $entities;
    }
}