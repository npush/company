<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/4/17
 * Time: 1:00 PM
 */
class Stableflow_Company_Model_Parser_Entity_Product extends Stableflow_Company_Model_Parser_Entity_Abstract
{
    const MANUFACTURER_ATTRIBUTE = 'manufacturer';
    const MANUFACTURER_CODE_ATTRIBUTE = 'manufacturer_number';
    const MANUFACTURER_CODE_DELIMITER = '|';

    const BEHAVIOR_ADD_NEW  = 'add_new';
    const BEHAVIOR_UPDATE   = 'update';
    const BEHAVIOR_DISABLE  = 'disable';
    const BEHAVIOR_DELETE   = 'delete';

    /**
     * Size of bunch - part of products to save in one step.
     */
    const BUNCH_SIZE = 20;

    /**
     * Default Scope
     */
    const SCOPE_DEFAULT = 1;

    /**
     * Website Scope
     */
    const SCOPE_WEBSITE = 2;

    /**
     * Store Scope
     */
    const SCOPE_STORE   = 0;

    /**#@+
     * Permanent column names.
     *
     */

    const COL_PRICE         = 'price';
    const COL_CODE          = 'code';
    const COL_MANUFACTURER  = 'manufacturer';
    const COL_COMPANY       = 'company';

    /**#@+
     * Error codes.
     */
    const ERROR_INVALID_SCOPE                = 'invalidScope';

    /**
     * Error - invalid website
     */
    const ERROR_INVALID_WEBSITE              = 'invalidWebsite';

    /**
     * Error - invalid store
     */
    const ERROR_INVALID_STORE                = 'invalidStore';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::ERROR_INVALID_SCOPE                => 'Invalid value in Scope column',
        self::ERROR_INVALID_WEBSITE              => 'Invalid value in Website column (website does not exists?)',
        self::ERROR_INVALID_STORE                => 'Invalid value in Store column (store does not exists?)',
    );

    protected $_eventPrefix = 'company_parser_entity_product';
    protected $_eventObject = 'product';

    protected $_manufacturers = null;

    /**
     * Run parsing process
     *
     * @param Stableflow_Company_Model_Parser_Task $task
     * @return bool
     * @throws Exception
     */
    public function _run($task)
    {
        $task->setProcessAt();
        $sheet = $this->getSource();
        //$params = array('object' => $this, 'field' => $field, 'value'=> $id);
        //$params = array_merge($params, $this->_getEventData());
        Mage::dispatchEvent($this->_eventPrefix.'_run_before', array($this->_eventObject => $this));
        // Iterate
        foreach($sheet as $row){
            if(!is_null($_lastPos = $task->getLastRow()) && $_lastPos != $sheet->key()){
                $sheet->seek($_lastPos);
                continue;
            }
            $data = new Varien_Object(array(
                'company_id'            => $task->getCompanyId(),
                'task_id'               => $task->getId(),
                'line_num'              => $sheet->key(),
                'content'               => serialize($row),
                'raw_data'              => $row,
                'catalog_product_id'    => null,
                'company_product_id'    => null
            ));
            $this->update($data);
            $task->setReadRowNum($sheet->key());
        }
        Mage::dispatchEvent($this->_eventPrefix.'_run_after', array($this->_eventObject => $this));
        $task->setSpentTime();
        $task->setStatus(Stableflow_Company_Model_Parser_Task_Status::STATUS_COMPLETE);
        $task->save();
        return true;
    }


    /**
     * Gather and save information about product entities.
     *
     * @return Mage_ImportExport_Model_Import_Entity_Product
     */
    protected function _saveProducts()
    {
        $priceIsGlobal  = Mage::helper('catalog')->isPriceGlobal();
        $productLimit   = null;
        $productsQty    = null;

        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityRowsIn = array();
            $entityRowsUp = array();
            $attributes   = array();
            $websites     = array();
            $categories   = array();
            $tierPrices   = array();
            $groupPrices  = array();
            $mediaGallery = array();
            $uploadedGalleryFiles = array();
            $previousType = null;
            $previousAttributeSet = null;

            foreach ($bunch as $rowNum => $rowData) {
                $this->_filterRowData($rowData);
                if (!$this->validateRow($rowData, $rowNum)) {
                    continue;
                }
                $rowScope = $this->getRowScope($rowData);

                if (self::SCOPE_DEFAULT == $rowScope) {
                    $rowSku = $rowData[self::COL_SKU];

                    // 1. Entity phase
                    if (isset($this->_oldSku[$rowSku])) { // existing row
                        $entityRowsUp[] = array(
                            'updated_at' => now(),
                            'entity_id'  => $this->_oldSku[$rowSku]['entity_id']
                        );
                    } else { // new row
                        if (!$productLimit || $productsQty < $productLimit) {
                            $entityRowsIn[$rowSku] = array(
                                'entity_type_id'   => $this->_entityTypeId,
                                'attribute_set_id' => $this->_newSku[$rowSku]['attr_set_id'],
                                'type_id'          => $this->_newSku[$rowSku]['type_id'],
                                'sku'              => $rowSku,
                                'created_at'       => now(),
                                'updated_at'       => now()
                            );
                            $productsQty++;
                        } else {
                            $rowSku = null; // sign for child rows to be skipped
                            $this->_rowsToSkip[$rowNum] = true;
                            continue;
                        }
                    }
                } elseif (null === $rowSku) {
                    $this->_rowsToSkip[$rowNum] = true;
                    continue; // skip rows when SKU is NULL
                } elseif (self::SCOPE_STORE == $rowScope) { // set necessary data from SCOPE_DEFAULT row
                    $rowData[self::COL_TYPE]     = $this->_newSku[$rowSku]['type_id'];
                    $rowData['attribute_set_id'] = $this->_newSku[$rowSku]['attr_set_id'];
                    $rowData[self::COL_ATTR_SET] = $this->_newSku[$rowSku]['attr_set_code'];
                }
                if (!empty($rowData['_product_websites'])) { // 2. Product-to-Website phase
                    $websites[$rowSku][$this->_websiteCodeToId[$rowData['_product_websites']]] = true;
                }

                // 3. Categories phase
                $categoryPath = empty($rowData[self::COL_CATEGORY]) ? '' : $rowData[self::COL_CATEGORY];
                if (!empty($rowData[self::COL_ROOT_CATEGORY])) {
                    $categoryId = $this->_categoriesWithRoots[$rowData[self::COL_ROOT_CATEGORY]][$categoryPath];
                    $categories[$rowSku][$categoryId] = true;
                } elseif (!empty($categoryPath)) {
                    $categories[$rowSku][$this->_categories[$categoryPath]] = true;
                }

                if (!empty($rowData['_tier_price_website'])) { // 4.1. Tier prices phase
                    $tierPrices[$rowSku][] = array(
                        'all_groups'        => $rowData['_tier_price_customer_group'] == self::VALUE_ALL,
                        'customer_group_id' => ($rowData['_tier_price_customer_group'] == self::VALUE_ALL)
                            ? 0 : $rowData['_tier_price_customer_group'],
                        'qty'               => $rowData['_tier_price_qty'],
                        'value'             => $rowData['_tier_price_price'],
                        'website_id'        => (self::VALUE_ALL == $rowData['_tier_price_website'] || $priceIsGlobal)
                            ? 0 : $this->_websiteCodeToId[$rowData['_tier_price_website']]
                    );
                }
                if (!empty($rowData['_group_price_website'])) { // 4.2. Group prices phase
                    $groupPrices[$rowSku][] = array(
                        'all_groups'        => $rowData['_group_price_customer_group'] == self::VALUE_ALL,
                        'customer_group_id' => ($rowData['_group_price_customer_group'] == self::VALUE_ALL)
                            ? 0 : $rowData['_group_price_customer_group'],
                        'value'             => $rowData['_group_price_price'],
                        'website_id'        => (self::VALUE_ALL == $rowData['_group_price_website'] || $priceIsGlobal)
                            ? 0 : $this->_websiteCodeToId[$rowData['_group_price_website']]
                    );
                }
                foreach ($this->_imagesArrayKeys as $imageCol) {
                    if (!empty($rowData[$imageCol])) { // 5. Media gallery phase
                        if (!array_key_exists($rowData[$imageCol], $uploadedGalleryFiles)) {
                            $uploadedGalleryFiles[$rowData[$imageCol]] = $this->_uploadMediaFiles($rowData[$imageCol]);
                        }
                        $rowData[$imageCol] = $uploadedGalleryFiles[$rowData[$imageCol]];
                    }
                }
                if (!empty($rowData['_media_image'])) {
                    $mediaGallery[$rowSku][] = array(
                        'attribute_id'      => $rowData['_media_attribute_id'],
                        'label'             => $rowData['_media_lable'],
                        'position'          => $rowData['_media_position'],
                        'disabled'          => $rowData['_media_is_disabled'],
                        'value'             => $rowData['_media_image']
                    );
                }
                // 6. Attributes phase
                $rowStore     = self::SCOPE_STORE == $rowScope ? $this->_storeCodeToId[$rowData[self::COL_STORE]] : 0;
                $productType  = isset($rowData[self::COL_TYPE]) ? $rowData[self::COL_TYPE] : null;
                if (!is_null($productType)) {
                    $previousType = $productType;
                }
                if (!is_null($rowData[self::COL_ATTR_SET])) {
                    $previousAttributeSet = $rowData[Mage_ImportExport_Model_Import_Entity_Product::COL_ATTR_SET];
                }
                if (self::SCOPE_NULL == $rowScope) {
                    // for multiselect attributes only
                    if (!is_null($previousAttributeSet)) {
                        $rowData[Mage_ImportExport_Model_Import_Entity_Product::COL_ATTR_SET] = $previousAttributeSet;
                    }
                    if (is_null($productType) && !is_null($previousType)) {
                        $productType = $previousType;
                    }
                    if (is_null($productType)) {
                        continue;
                    }
                }
                $rowData = $this->_productTypeModels[$productType]->prepareAttributesForSave(
                    $rowData,
                    !isset($this->_oldSku[$rowSku]) && (self::SCOPE_DEFAULT == $rowScope)
                );
                try {
                    $attributes = $this->_prepareAttributes($rowData, $rowScope, $attributes, $rowSku, $rowStore);
                } catch (Exception $e) {
                    Mage::logException($e);
                    continue;
                }
            }

            $this->_saveProductEntity($entityRowsIn, $entityRowsUp)
            //    ->_saveProductAttributes($attributes)
            ;
        }
        return $this;
    }

    /**
     * Update and insert data in entity table.
     *
     * @param array $entityRowsIn Row for insert
     * @param array $entityRowsUp Row for update
     * @return Mage_ImportExport_Model_Import_Entity_Product
     */
    protected function _saveProductEntity(array $entityRowsIn, array $entityRowsUp)
    {
        static $entityTable = null;

        if (!$entityTable) {
            $entityTable = Mage::getModel('importexport/import_proxy_product_resource')->getEntityTable();
        }
        if ($entityRowsUp) {
            $this->_connection->insertOnDuplicate(
                $entityTable,
                $entityRowsUp,
                array('updated_at')
            );
        }
        if ($entityRowsIn) {
            $this->_connection->insertMultiple($entityTable, $entityRowsIn);

            $newProducts = $this->_connection->fetchPairs($this->_connection->select()
                ->from($entityTable, array('sku', 'entity_id'))
                ->where('sku IN (?)', array_keys($entityRowsIn))
            );
            foreach ($newProducts as $sku => $newId) { // fill up entity_id for new products
                $this->_newSku[$sku]['entity_id'] = $newId;
            }
        }
        return $this;
    }

    /**
     * Update / add new / delete Company Product
     * @param $data Varien_Object
     */
    public function update($data)
    {
        $message = null;
        Mage::dispatchEvent($this->_eventPrefix.'_update_before', array('update_data' => $data, 'message' => $message));
        $row = $data->getRawData();
        if(!$this->_isValidRow($row)){
            // empty code
            $message = Mage::getSingleton('company/parser_log_message')->error($data);
        }elseif($result = $this->findByCode($row['code'], $data->getCompanyId(), $row['manufacturer'])){
            // found product
            if($result['company_product_id']){
                //update company product
                $this->_productRoutine($data, self::BEHAVIOR_UPDATE, $data->getCompanyId(), $result['catalog_product_id'], $result['company_product_id']);
            }else{
                // add new company product
                $newProduct = $this->_productRoutine($data, self::BEHAVIOR_ADD_NEW, $data->getCompanyId(), $result['catalog_product_id']);
                $result['company_product_id'] = $newProduct->getId();
            }
            $data->setCatalogProductId($result['catalog_product_id']);
            $data->setCompanyProductId($result['company_product_id']);
            $message = Mage::getSingleton('company/parser_log_message')->success($data);
            }else{
            // code did not found
            $message = Mage::getSingleton('company/parser_log_message')->error($data);
        }
        Mage::dispatchEvent($this->_eventPrefix.'_update_after', array('update_data' => $data, 'message' => $message));
    }

    /**
     * Check row for valid data
     * @param $row array
     * @return bool
     */
    protected function _isValidRow(&$row)
    {
        if($row['code'] == ''){
            $row['code'] = 'empty code';
            return false;
        }
        return true;
    }

    /**
     * @param $row Varien_Object
     * @param $behavior int
     * @param $companyId int
     * @param $baseProductId int
     * @param $productId int
     * @return int
     */
    protected function _productRoutine($row, $behavior, $companyId, $baseProductId, $productId = null)
    {
        $product = Mage::getModel('company/product');
        switch($behavior){
            case self::BEHAVIOR_ADD_NEW :
                $_data = array(
                    'price' => $row->getRawData()['price'],
                    'price_int' => $row->getRawData()['price_internal'],
                    'price_wholesale' => $row->getRawData()['price_wholesale'],
                    'catalog_product_id' =>  $baseProductId,
                    'company_id'    => $companyId,
                    'store_id'  => 0,
                    'created_at' => Varien_Date::now(),
                    'updated_at' => Varien_Date::now(),
                );
                $product->addData($_data);
                //$productId = 654321;
                $productId = $product->save();
                break;
            case self::BEHAVIOR_UPDATE :
                $product->load($productId);
                $_data = array(
                    'price' => $row->getRawData()['price'],
                    'price_int' => $row->getRawData()['price_internal'],
                    'price_wholesale' => $row->getRawData()['price_wholesale'],
                    'updated_at' => Varien_Date::now(),
                );
                $product->addData($_data);
                $product->save();
                break;
            case self::BEHAVIOR_DISABLE :
                $product->load($productId);
                $product->setStatust(Stableflow_Company_Model_Product_Status::DISABLE);
                $product->save();
                break;
            case self::BEHAVIOR_DELETE :
                $product->load($productId);
                $product->delete();
                break;
        }
        return $productId;
    }

    /**
     * Find product by manufacture code
     * @param $code string
     * @param $companyId int
     * @param $manufacturer string
     * @return array
     */
    public function findByCode($code, $companyId, $manufacturer)
    {
        $companyProductId = null;
        $catalogProductId = null;
        $manufacturerId = $this->getManufacturerIdByName($manufacturer);
        $mfCodeAttribute = Mage::getModel('eav/entity_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, self::MANUFACTURER_CODE_ATTRIBUTE);
        $mfNameAttribute = Mage::getModel('eav/entity_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, self::MANUFACTURER_ATTRIBUTE);
        $productCollection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToFilter($mfCodeAttribute, array('like' => '%'.$code.'%'))
            ->addAttributeToFilter($mfNameAttribute, array('en' => $manufacturerId))
            ->addAttributeToSelect(array('entity_id',self::MANUFACTURER_CODE_ATTRIBUTE , self::MANUFACTURER_ATTRIBUTE))
            ->initCache(Mage::app()->getCache(),'parser_catalog_collection',array('SOME_TAGS'));
        foreach($productCollection as $_product) {
            $catalogProductId = $_product->getId();
            $manufacturerCode = $_product->getData(self::MANUFACTURER_CODE_ATTRIBUTE);
            $_codes = explode(self::MANUFACTURER_CODE_DELIMITER, $manufacturerCode);
            if(in_array($code, $_codes)){
                $companyProductId = Mage::getResourceModel('company/product_collection')
                    ->addAttributeToFilter('catalog_product_id', $catalogProductId)
                    ->addAttributeToFilter('company_id', $companyId)
                    ->addAttributeToSelect(array('entity_id', 'catalog_product_id', 'company_id'))
                    ->initCache(Mage::app()->getCache(),'parser_company_product_collection',array('SOME_TAGS'))
                    ->getFirstItem()
                    ->getId();
                return array(
                    'catalog_product_id' => $catalogProductId,
                    'company_product_id' => $companyProductId
                );
            }
        }
        return null;
    }

    /**
     * @return array [id] => Name
     */
    public function getManufacturers()
    {
        if(is_null($this->_manufacturers)) {
            $attribute = Mage::getModel('eav/entity_attribute')
                ->loadByCode(Mage_Catalog_Model_Product::ENTITY, self::MANUFACTURER_ATTRIBUTE);
            //$this->_manufacturers = $attribute->getSource()->getOptionArray();
            $this->_manufacturers = $attribute->getSource()->getAllOptions();
        }
        return $this->_manufacturers;
    }

    /**
     * Get id by Name
     * @param $name string
     * @return int
     */
    public function getManufacturerIdByName($name)
    {
        $manufacturers = $this->getManufacturers();
        foreach($manufacturers as $_id => $_name){
            if(strcasecmp($_name ,$name) == 0){
                return $_id;
            }
        }
        return null;
    }

    protected function _getEventData()
    {
        return array(
            'data_object'       => $this,
            $this->_eventObject => $this,
        );
    }
}