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
    const BUNCH_SIZE = 50;

    /**#@+
     * Permanent column names.
     *
     */

    const COL_PRICE             = 'price';
    const COL_WHOLESALE_PRICE   = 'wholesale price';
    const COL_INTERNAL_PRICE    = 'internal price';
    const COL_CODE              = 'code';
    const COL_QTY               = 'qty';
    const COL_MANUFACTURER      = 'manufacturer';
    const COL_COMPANY           = 'company';

    /**
     *
     */
    const ERROR_UNKNOWN                 = 'unknown error';

    /**
     * Error - invalid price
     */
    const ERROR_INVALID_PRICE           = 'invalidPrice';

    /**
     * Error - invalid code
     */
    const ERROR_INVALID_CODE            = 'invalidCode';

    /**
     * Error - code no found
     */
    const ERROR_CODE_NOT_FOUND          = 'codeNotFound';

    /**
     * Error - Manufacturer not found
     */
    const ERROR_MANUFACTURER_NOT_FOUND  = 'manufacturerNotFound';

    /**
     * Error - Base product with code not found
     */
    const ERROR_BASE_PRODUCT_NOT_FOUND  = 'baseProductNotFound';

    /**
     * SUCCESS - Company product added
     */
    const SUCCESS_PRODUCT_ADDED         = 'ProductAdded';

    /**
     * SUCCESS - Company product updated
     */
    const SUCCESS_PRODUCT_UPDATED       = 'ProductUpdated';

    /**
     * SUCCESS - Company product disabled
     */
    const SUCCESS_PRODUCT_DISABLED      = 'ProductDisabled';

    /**
     * SUCCESS - Company product deleted
     */
    const SUCCESS_PRODUCT_DELETED       = 'ProductDeleted';

    const BEHAVIOR_NOT_FOUND            = 'BEHAVIOR_NOT_FOUND';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::ERROR_INVALID_PRICE               => 'Invalid Price',
        self::ERROR_INVALID_CODE                => 'Invalid Code',
        self::ERROR_CODE_NOT_FOUND              => 'Code Not Found',
        self::ERROR_MANUFACTURER_NOT_FOUND      => 'Manufacturer not found',
        self::ERROR_BASE_PRODUCT_NOT_FOUND      => 'Base product not found',
        self::SUCCESS_PRODUCT_ADDED             => 'Company product added',
        self::SUCCESS_PRODUCT_UPDATED           => 'Company product updated',
        self::SUCCESS_PRODUCT_DISABLED          => 'Company product disabled',
        self::SUCCESS_PRODUCT_DELETED           => 'Company product deleted',
        self::BEHAVIOR_NOT_FOUND                => 'BEHAVIOR_NOT_FOUND'
    );

    protected $_eventPrefix = 'company_parser_entity_product';
    protected $_eventObject = 'product';

    protected $_manufacturers = null;

    protected $_processedData = array(
        'manufacturer_id'       => null,
        'manufacturer_code'     => null,
        'catalog_product_id'    => null,
        'company_product_id'    => null,
        'company_id'            => null,
        'line_num'              => null,
        'task_id'               => null
    );

    /**
     * Run parsing process
     *
     * @return bool
     * @throws Exception
     */
    public function runParsingProcess()
    {
        $this->getTask()->setProcessAt();
        //$params = array('object' => $this, 'field' => $field, 'value'=> $id);
        //$params = array_merge($params, $this->_getEventData());
        Mage::dispatchEvent($this->_eventPrefix.'_run_before', array($this->_eventObject => $this));
        while($bunch = $this->parseSource()){
            $this->_saveProducts($bunch);
        }

        Mage::log($this->getMessages(), null, 'success-product.log');
        Mage::dispatchEvent($this->_eventPrefix.'_run_after', array($this->_eventObject => $this));
        $this->getTask()->setComplete();
        return true;
    }

    protected function parseSource($rewind = null)
    {
        // Iterate
        $idx = 0;
        $updateData = array();
        if($rewind){
            $this->getSource()->rewind();
        }
        if($_lastPos = $this->getTask()->checkPosition($this->_getLineNumber())){
            $this->getSource()->seek($_lastPos);
        }
        while($idx < self::BUNCH_SIZE) {
            if(!$this->getSource()->valid()){
                break;
            }
            $row = $this->getSource()->current();
            if(!$this->_isValidRow($row)){
                $this->addRowError(self::ERROR_UNKNOWN, $row, array(), $this->_getLineNumber());
                $this->getSource()->next();
                continue;
            }
//            $rows[$idx] = $row;
//            $codes[$idx] = $row['code'];
//            $manufac[$idx] = $row['manufacturer'];
            try {
                $find = $this->findByCode($row['code'], $row['manufacturer'], $this->_getCompanyId());
                if (is_null($find['catalog_product_id'])) {

                }
                if (!is_null($find['company_product_id']) && !is_null($find['catalog_product_id'])) {
                    //update company product
                    $updateData[self::BEHAVIOR_UPDATE][$idx]['update_data'] = $this->_processedData;
                    $updateData[self::BEHAVIOR_UPDATE][$idx]['update_data'] = array_merge($updateData[self::BEHAVIOR_UPDATE][$idx]['update_data'], $find);
                    $updateData[self::BEHAVIOR_UPDATE][$idx]['update_data']['task_id'] = $this->_getTaskId();
                    $updateData[self::BEHAVIOR_UPDATE][$idx]['update_data']['line_num'] = $this->_getLineNumber();
                    $updateData[self::BEHAVIOR_UPDATE][$idx]['update_data']['company_id'] = $this->_getCompanyId();
                    $updateData[self::BEHAVIOR_UPDATE][$idx]['update_data']['manufacturer_id'] = $row['manufacturer'];
                    $updateData[self::BEHAVIOR_UPDATE][$idx]['update_data']['manufacturer_code'] = $row['code'];
                    $updateData[self::BEHAVIOR_UPDATE][$idx]['raw_data'] = $row;
                }
                if (is_null($find['company_product_id']) && !is_null($find['catalog_product_id'])) {
                    // add new company product
                    $updateData[self::BEHAVIOR_ADD_NEW][$idx]['update_data'] = $this->_processedData;
                    $updateData[self::BEHAVIOR_ADD_NEW][$idx]['update_data'] = array_merge($updateData[self::BEHAVIOR_ADD_NEW][$idx]['update_data'], $find);
                    $updateData[self::BEHAVIOR_ADD_NEW][$idx]['update_data']['task_id'] = $this->_getTaskId();
                    $updateData[self::BEHAVIOR_ADD_NEW][$idx]['update_data']['line_num'] = $this->_getLineNumber();
                    $updateData[self::BEHAVIOR_ADD_NEW][$idx]['update_data']['company_id'] = $this->_getCompanyId();
                    $updateData[self::BEHAVIOR_ADD_NEW][$idx]['update_data']['manufacturer_id'] = $row['manufacturer'];
                    $updateData[self::BEHAVIOR_ADD_NEW][$idx]['update_data']['manufacturer_code'] = $row['code'];
                    $updateData[self::BEHAVIOR_ADD_NEW][$idx]['raw_data'] = $row;
                }
            } catch (Stableflow_Company_Exception $e) {
                $this->addRowError($e->getMessage(), $row, $updateData, $this->_getLineNumber());
            } catch (PHPExcel_Exception $e) {

            }
            catch (Exception $e) {

            }
            finally{
                $this->getSource()->next();
                $idx++;
            }
        }
        return $updateData;
    }

    /**
     * @param array $updateData
     */
    protected function _saveProducts(array $updateData)
    {
        foreach($updateData as $behavior => $data){
            switch ($behavior){
                case self::BEHAVIOR_UPDATE:
                    foreach($data as $row){
                        $entityRowsUp[] = array(
                            'updated_at' => now(),
                            'entity_id'  => $row['update_data']['company_product_id']
                        );
                        $this->_productRoutine($row['raw_data'], $row['update_data'], $behavior);
                        $this->addMessage(self::SUCCESS_PRODUCT_UPDATED, $row['raw_data'], $row['update_data'], $row['update_data']['line_num']);
                    }
                    break;
                case self::BEHAVIOR_ADD_NEW:
                    foreach($data as $row){
                        $entityRowsIn[$rowSku] = array(
                            'entity_type_id'   => $this->_entityTypeId,
                            'attribute_set_id' => $this->_newSku[$rowSku]['attr_set_id'],
                            'type_id'          => $this->_newSku[$rowSku]['type_id'],
                            'created_at'       => now(),
                            'updated_at'       => now()
                        );
                        $this->_prepareAttributes($row['update_data']);


                        $newProduct = $this->_productRoutine($row['raw_data'], $row['update_data'], $behavior);
                        $data['update_data']['company_product_id'] = $newProduct->getId();
                        $this->addMessage(self::SUCCESS_PRODUCT_UPDATED, $row['raw_data'], $row['update_data'], $row['update_data']['line_num']);
                    }
                    break;
                case self::BEHAVIOR_DISABLE:
                    break;
                case self::BEHAVIOR_DELETE:
                    break;
                default:
                    Mage::throwException(self::BEHAVIOR_NOT_FOUND);
            }
        }
    }


    /**
     * Delete products.
     *
     * @param array $updateData
     * @return Stableflow_Company_Model_Parser_Entity_Product
     */
    protected function _deleteProducts($updateData)
    {
        $productEntityTable = Mage::getResourceModel('catalog/product')->getEntityTable();
        $idToDelete = array();

        if ($idToDelete) {
            $this->_connection->query($this->_connection->quoteInto(
                "DELETE FROM `{$productEntityTable}` WHERE `entity_id` IN (?)", $idToDelete
                ));
        }


        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $idToDelete = array();

            foreach ($bunch as $rowNum => $rowData) {
                if ($this->validateRow($rowData, $rowNum) && self::SCOPE_DEFAULT == $this->getRowScope($rowData)) {
                    $idToDelete[] = $this->_oldSku[$rowData[self::COL_SKU]]['entity_id'];
                }
            }
            if ($idToDelete) {
                $this->_connection->query(
                    $this->_connection->quoteInto(
                        "DELETE FROM `{$productEntityTable}` WHERE `entity_id` IN (?)", $idToDelete
                    )
                );
            }
        }
        return $this;
    }

    /**
     * Prepare attributes data
     *
     * @param array $attrData
     * @param array $attributes
     * @return array
     */
    protected function _prepareAttributes($attrData, $attributes)
    {
        $product = Mage::getModel('company/product', $attrData);
        foreach ($attrData as $attrCode => $attrValue) {
            $attribute = $this->_getAttribute($attrCode);
            $attrId = $attribute->getId();
            $backModel = $attribute->getBackendModel();
            $attrTable = $attribute->getBackend()->getTable();

            if ('datetime' == $attribute->getBackendType() && strtotime($attrValue)) {
                $attrValue = gmstrftime($this->_getStrftimeFormat(), strtotime($attrValue));
            } elseif ($backModel) {
                $attribute->getBackend()->beforeSave($product);
                $attrValue = $product->getData($attribute->getAttributeCode());
            }
            if ('multiselect' == $attribute->getFrontendInput()) {
                if (!isset($attributes[$attrTable][$rowSku][$attrId])) {
                    $attributes[$attrTable][$rowSku][$attrId] = '';
                } else {
                    $attributes[$attrTable][$rowSku][$attrId .= ',';
                }
                $attributes[$attrTable][$rowSku][$attrId] .= $attrValue;
            } else {
                $attributes[$attrTable][$rowSku][$attrId] = $attrValue;
            }
            $attribute->setBackendModel($backModel); // restore 'backend_model' to avoid 'default' setting
        }
        return $attributes;
    }

    /**
     * Save product attributes.
     *
     * @param array $attributesData
     * @return Stableflow_Company_Model_Parser_Entity_Product
     */
    protected function _saveProductAttributes(array $attributesData)
    {
        foreach ($attributesData as $tableName => $skuData) {
            $tableData = array();

            foreach ($skuData as $sku => $attributes) {
                $productId = $this->_newSku[$sku]['entity_id'];

                foreach ($attributes as $attributeId => $storeValues) {
                    foreach ($storeValues as $storeId => $storeValue) {
                        $tableData[] = array(
                            'entity_id'      => $productId,
                            'entity_type_id' => $this->_entityTypeId,
                            'attribute_id'   => $attributeId,
                            'store_id'       => $storeId,
                            'value'          => $storeValue
                        );
                    }
                }
            }
            $this->_connection->insertOnDuplicate($tableName, $tableData, array('value'));
        }
        return $this;
    }

    /**
     * Gather and save information about product entities.
     *
     * @return Stableflow_Company_Model_Parser_Entity_Product
     */
    protected function _saveProducts_()
    {
        $productLimit   = null;
        $productsQty    = null;

        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityRowsIn = array();
            $entityRowsUp = array();
            $attributes   = array();
            $websites     = array();
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
                ->_saveProductAttributes($attributes);
        }
        return $this;
    }

    /**
     * Update and insert data in entity table.
     *
     * @param array $entityRowsIn Row for insert
     * @param array $entityRowsUp Row for update
     * @return Stableflow_Company_Model_Parser_Entity_Product
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
     * Retrieve attribute by specified code
     *
     * @param string $code
     * @return Mage_Eav_Model_Entity_Attribute_Abstract
     */
    protected function _getAttribute($code)
    {
        $attribute = Mage::getSingleton('company/resource_product')->getAttribute($code);
        $backendModelName = (string)Mage::getConfig()->getNode(
            'global/parser/import/catalog_product/attributes/' . $attribute->getAttributeCode() . '/backend_model'
        );
        if (!empty($backendModelName)) {
            $attribute->setBackendModel($backendModelName);
        }
        return $attribute;
    }

    /**
     * Prepare attributes data
     *
     * @param array $rowData
     * @param int $rowScope
     * @param array $attributes
     * @param string|null $rowSku
     * @param int $rowStore
     * @return array
     */
    protected function _prepareAttributes_($rowData, $rowScope, $attributes, $rowSku, $rowStore)
    {
        $product = Mage::getModel('importexport/import_proxy_product', $rowData);

        foreach ($rowData as $attrCode => $attrValue) {
            $attribute = $this->_getAttribute($attrCode);
            if ('multiselect' != $attribute->getFrontendInput()
                && self::SCOPE_NULL == $rowScope
            ) {
                continue; // skip attribute processing for SCOPE_NULL rows
            }
            $attrId = $attribute->getId();
            $backModel = $attribute->getBackendModel();
            $attrTable = $attribute->getBackend()->getTable();
            $storeIds = array(0);

            if ('datetime' == $attribute->getBackendType() && strtotime($attrValue)) {
                $attrValue = gmstrftime($this->_getStrftimeFormat(), strtotime($attrValue));
            } elseif ('url_key' == $attribute->getAttributeCode()) {
                if (empty($attrValue)) {
                    $attrValue = $product->formatUrlKey($product->getName());
                }
            } elseif ($backModel) {
                $attribute->getBackend()->beforeSave($product);
                $attrValue = $product->getData($attribute->getAttributeCode());
            }
            if (self::SCOPE_STORE == $rowScope) {
                if (self::SCOPE_WEBSITE == $attribute->getIsGlobal()) {
                    // check website defaults already set
                    if (!isset($attributes[$attrTable][$rowSku][$attrId][$rowStore])) {
                        $storeIds = $this->_storeIdToWebsiteStoreIds[$rowStore];
                    }
                } elseif (self::SCOPE_STORE == $attribute->getIsGlobal()) {
                    $storeIds = array($rowStore);
                }
            }
            foreach ($storeIds as $storeId) {
                if ('multiselect' == $attribute->getFrontendInput()) {
                    if (!isset($attributes[$attrTable][$rowSku][$attrId][$storeId])) {
                        $attributes[$attrTable][$rowSku][$attrId][$storeId] = '';
                    } else {
                        $attributes[$attrTable][$rowSku][$attrId][$storeId] .= ',';
                    }
                    $attributes[$attrTable][$rowSku][$attrId][$storeId] .= $attrValue;
                } else {
                    $attributes[$attrTable][$rowSku][$attrId][$storeId] = $attrValue;
                }
            }
            $attribute->setBackendModel($backModel); // restore 'backend_model' to avoid 'default' setting
        }
        return $attributes;
    }


    /**
     * Check row for valid data
     * @param $row array
     * @return bool
     */
    protected function _isValidRow(&$row)
    {
        if(!is_numeric($row['price'])){
            $row['price'] = "0.00";
        }
        if($row['code'] == ''){
            $row['code'] = 'empty code';
            return false;
        }
        return true;
    }

    /**
     * @param array $row
     * @param array $updateRow
     * @param int $behavior
     * @return int
     */
    protected function _productRoutine($row, $updateRow, $behavior)
    {
        $product = Mage::getModel('company/product');
        switch($behavior){
            case self::BEHAVIOR_ADD_NEW :
                $_data = array(
                    'price'             => $row['price'],
                    'price_int'         => $row['price_internal'],
                    'price_wholesale'   => $row['price_wholesale'],
                    'catalog_product_id' => $updateRow['catalog_product_id'],
                    'company_id'        => $updateRow['company_id'],
                    'store_id'          => 0,
                    'created_at'        => Varien_Date::now(),
                    'updated_at'        => Varien_Date::now(),
                );
                $product->addData($_data);
                //$productId = 654321;
                $productId = $product->save();
                break;
            case self::BEHAVIOR_UPDATE :
                $product->load($updateRow['company_product_id']);
                $_data = array(
                    'price'             => $row['price'],
                    'price_int'         => $row['price_internal'],
                    'price_wholesale'   => $row['price_wholesale'],
                    'updated_at'        => Varien_Date::now(),
                );
                $product->addData($_data);
                $productId = $product->save();
                break;
            case self::BEHAVIOR_DISABLE :
                $product->load($updateRow['company_product_id']);
                $product->setStatust(Stableflow_Company_Model_Product_Status::DISABLE);
                $productId = $product->save();
                break;
            case self::BEHAVIOR_DELETE :
                $product->load($updateRow['company_product_id']);
                $productId = $product->delete();
                break;
        }
        return $productId;
    }

    /**
     * Find product by manufacture code
     * @param int $code
     * @param string $manufacturer
     * @param int $companyId
     * @return mixed status code
     */
    public function findByCode($code, $manufacturer, $companyId)
    {
        $manufacturerId = $this->getManufacturerIdByName($manufacturer);
        if(!$manufacturerId){
            //manufacturer did not found
            Mage::throwException(self::ERROR_MANUFACTURER_NOT_FOUND);
        }
        $productCollection = $this->findBaseProductByCode($code, $manufacturerId);
        if($productCollection->getSize() == 0) {
            // base product did not found
            Mage::throwException(self::ERROR_BASE_PRODUCT_NOT_FOUND);
        }
        $result = array(
            'catalog_product_id' => null,
            'company_product_id' => null,
        );

        // Base product found. Try to find company product
        /** @var Mage_Catalog_Model_Product $_product */
        foreach ($productCollection as $_product) {
            $catalogProductId = $_product->getId();
            $manufacturerCode = $_product->getData(self::MANUFACTURER_CODE_ATTRIBUTE);
            $_codes = explode(self::MANUFACTURER_CODE_DELIMITER, $manufacturerCode);
            if (in_array($code, $_codes) && $companyProduct = $this->findCompanyProduct($catalogProductId, $companyId)) {
                // company product found
                $result['catalog_product_id'] = $catalogProductId;
                $result['company_product_id'] = $companyProduct->getId();
            }else{
                // company product did not found
                $result['catalog_product_id'] = $catalogProductId;
            }
        }
        return $result;
    }

    /**
     * @param string $code
     * @param int $manufacturerId
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function findBaseProductByCode($code, $manufacturerId)
    {
        $mfCodeAttribute = Mage::getModel('eav/entity_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, self::MANUFACTURER_CODE_ATTRIBUTE);
        $mfNameAttribute = Mage::getModel('eav/entity_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, self::MANUFACTURER_ATTRIBUTE);
        return Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToFilter($mfNameAttribute, array('eq' => $manufacturerId))
            ->addAttributeToFilter($mfCodeAttribute, array('like' => '%'.$code.'%'))
            ->addAttributeToSelect(array('entity_id',self::MANUFACTURER_CODE_ATTRIBUTE , self::MANUFACTURER_ATTRIBUTE))
            ->initCache(Mage::app()->getCache(),'parser_catalog_product_collection',array('SOME_TAGS'));
    }

    /**
     * @param $catalogProductId
     * @param $companyId
     * @return Stableflow_Company_Model_Product
     */
    public function findCompanyProduct($catalogProductId, $companyId)
    {
        return Mage::getResourceModel('company/product_collection')
            ->addAttributeToFilter('catalog_product_id', $catalogProductId)
            ->addAttributeToFilter('company_id', $companyId)
            ->addAttributeToSelect(array('entity_id', 'catalog_product_id', 'company_id'))
            ->initCache(Mage::app()->getCache(),'parser_company_product_collection',array('SOME_TAGS'))
            ->getFirstItem();
    }

    /**
     * @return array [id] => Name
     */
    public function getManufacturers()
    {
        if(is_null($this->_manufacturers)) {
            $attribute = Mage::getModel('eav/entity_attribute')
                ->loadByCode(Mage_Catalog_Model_Product::ENTITY, self::MANUFACTURER_ATTRIBUTE);
            $this->_manufacturers = $attribute->getSource()->getOptionArray();
            //$this->_manufacturers = $attribute->getSource()->getAllOptions();
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
        foreach($this->getManufacturers() as $_id => $_name){
            if(strcasecmp($_name ,trim($name)) == 0){
                return $_id;
            }
        }
        return false;
    }


    protected function _getCompanyId()
    {
        return $this->getTask()->getCompanyId();
    }

    protected function _getTaskId()
    {
        return $this->getTask()->getId();
    }

    protected function _getLineNumber()
    {
        return $this->getSource()->key();
    }

    protected function _getEventData()
    {
        return array(
            'data_object'       => $this,
            $this->_eventObject => $this,
        );
    }
}