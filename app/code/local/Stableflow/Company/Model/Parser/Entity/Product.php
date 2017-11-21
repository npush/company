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

    /**
     * Error
     */
    const BEHAVIOR_NOT_FOUND            = 'BehaviorNotFound';

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
        self::BEHAVIOR_NOT_FOUND                => 'Behavior not found'
    );

    protected $_eventPrefix = 'company_parser_entity_product';
    protected $_eventObject = 'product';

    protected $_manufacturers = null;

    protected $_newCompProdIds;

    protected $_processedData = array(
        'manufacturer_id'       => null,
        'manufacturer_code'     => null,
        'catalog_product_id'    => null,
        'company_product_id'    => null,
        'company_id'            => null,
        'line_num'              => null,
        'task_id'               => null
    );

    protected $_permanentAttr = array(
        'price'             => 'price',
        'price_internal'    => 'price_int',
        'price_wholesale'   => 'price_wholesale',
        'qty_in_stock'      => 'qty',
        'name'              => 'name'
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
        Mage::dispatchEvent($this->_eventPrefix.'_run_after', array($this->_eventObject => $this));
        $this->getTask()->setComplete();
        return true;
    }

    /**
     *
     * @param null $rewind
     * @return array|bool
     */
    protected function parseSource($rewind = null)
    {
        $idx = 0;
        $entityRowsIn = array();
        $entityRowsUp = array();
        $attributes = array();
        if($rewind){
            $this->getSource()->rewind();
        }
        if($_lastPos = $this->getTask()->checkPosition($this->_getLineNumber())){
            $this->getSource()->seek($_lastPos);
        }
        while($this->getSource()->valid() && $idx < self::BUNCH_SIZE){
            $row = $this->getSource()->current();
            try {
                if(!$this->_isValidRow($row)){
                    throw new Stableflow_Company_Exception(self::ERROR_UNKNOWN);
                }
                $find = $this->findByCode($row['code'], $row['manufacturer'], $this->_getCompanyId());
                $catProdId = $find['catalog_product_id'];
                $compProdId = $find['company_product_id'];
                $behavior = is_null($compProdId) ? self::BEHAVIOR_ADD_NEW : self::BEHAVIOR_UPDATE;
                switch ($behavior){
                    case self::BEHAVIOR_UPDATE:
                        $entityRowsUp[$catProdId] = array(
                            'updated_at' => now(),
                            'entity_id'  => $compProdId,
                        );
                        $this->_newCompProdIds[$catProdId]['entity_id'] = $compProdId;
                        $attributes[$catProdId] = $this->_extractAttributes($row);
                        $this->addMessage(self::BEHAVIOR_UPDATE, $row, array(), $this->_getLineNumber());
                        break;
                    case self::BEHAVIOR_ADD_NEW:
                        $entityRowsIn[$catProdId] = array(
                            'entity_type_id'        => $this->_entityTypeId,
                            'catalog_product_id'    => $catProdId,
                            'company_id'            => $this->_getCompanyId(),
                            'attribute_set_id'      => 0,
                            'is_active'             => 1,
                            'created_at'            => now(),
                            'updated_at'            => now()
                        );
                        $attributes[$catProdId] = $this->_extractAttributes($row);
                        $this->addMessage(self::BEHAVIOR_ADD_NEW, $row, array(), $this->_getLineNumber());
                        break;
                    case self::BEHAVIOR_DISABLE:
                        break;
                    case self::BEHAVIOR_DELETE:
                        break;
                    default:
                        throw new Stableflow_Company_Exception(self::BEHAVIOR_NOT_FOUND);
                }
                $idx++;
            } catch (Stableflow_Company_Exception $e) {
                $this->addRowError($e->getMessage(), $row, array(), $this->_getLineNumber());
            }
            catch (Mage_Core_Exception $e) {
                Mage::logException($e->getMessages());
            }
            finally{
                $this->getSource()->next();
            }
        }
        return $idx ? array(
            'entityRowsUp' => $entityRowsUp,
            'entityRowsIn' => $entityRowsIn,
            'attributes' => $attributes,
            'idx'       => $idx
        ) : false;
    }

    /**
     * Save products
     *
     * @param array $bunch
     */
    protected function _saveProducts(array $bunch)
    {
        try{
            $this->_saveProductEntity($bunch['entityRowsIn'], $bunch['entityRowsUp']);
            $attributes = $this->_prepareAttributes($bunch['attributes']);
            $this->_saveProductAttributes($attributes);
        }catch (Mage_Core_Exception $e){
            Mage::logException($e->getMessages());
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
                "DELETE FROM `{$productEntityTable}` WHERE `entity_id` IN (?) AND `company_id` (?)", $idToDelete, $this->_getCompanyId()
                ));
        }

        return $this;
    }

    /**
     * Extract attributes from row, and convert them to correspond format
     *
     * @param $row
     * @return array
     */
    protected function _extractAttributes($row)
    {
        $attributes = array();
        foreach($row as $key => $value){
            if(array_key_exists($key, $this->_permanentAttr)){
                $attributes[$this->_permanentAttr[$key]] = $value;
            }
        }
        return $attributes;
    }

    /**
     * Prepare attributes data
     *
     * @param array $attr
     * @return array
     */
    protected function _prepareAttributes($attr)
    {
        $attributes = array();
        foreach ($attr as $catProdId => $attrData) {
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
                    if (!isset($attributes[$attrTable][$catProdId][$attrId])) {
                        $attributes[$attrTable][$catProdId][$attrId] = '';
                    } else {
                        $attributes[$attrTable][$catProdId][$attrId] .= ',';
                    }
                    $attributes[$attrTable][$catProdId][$attrId] .= $attrValue;
                } else {
                    $attributes[$attrTable][$catProdId][$attrId] = $attrValue;
                }
                $attribute->setBackendModel($backModel); // restore 'backend_model' to avoid 'default' setting
            }
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

            foreach ($skuData as $catProdId => $attributes) {
                $productId = $this->_newCompProdIds[$catProdId]['entity_id'];

                foreach ($attributes as $attributeId => $value) {
                    $tableData[] = array(
                        'entity_id'      => $productId,
                        'entity_type_id' => $this->_entityTypeId,
                        'attribute_id'   => $attributeId,
                        'store_id'       => 0,
                        'value'          => $value
                    );
                }

                $where = $this->_connection->quoteInto('attribute_id = ?', $attributeId) .
                    $this->_connection->quoteInto(' AND entity_id = ?', $productId) .
                    $this->_connection->quoteInto(' AND entity_type_id = ?', $this->_entityTypeId);

                $this->_connection->delete(
                    $tableName, $where
                );
            }
            $this->_connection->insertOnDuplicate($tableName, $tableData, array('value'));
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
            $entityTable = Mage::getResourceModel('company/product')->getEntityTable();
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
                ->from($entityTable, array('catalog_product_id', 'entity_id'))
                ->where('catalog_product_id IN (?)', array_keys($entityRowsIn))
                ->where('company_id = ?', $this->_getCompanyId())
            );
            foreach ($newProducts as $catProdId => $newId) { // fill up entity_id for new products
                $this->_newCompProdIds[$catProdId]['entity_id'] = $newId;
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
     * Find product by manufacture code
     * @param int $code
     * @param string $manufacturer
     * @param int $companyId
     * @throws Stableflow_Company_Exception
     * @return mixed status code
     */
    public function findByCode($code, $manufacturer, $companyId)
    {
        #Varien_Profiler::start(__METHOD__.'/start');
        $manufacturerId = $this->getManufacturerIdByName($manufacturer);
        if(!$manufacturerId){
            //manufacturer did not found
            $message = sprintf('%s in string %s',self::ERROR_MANUFACTURER_NOT_FOUND, $this->_getLineNumber());
            throw new Stableflow_Company_Exception($message);
        }
        $additionalCode = Mage::getModel('company/parser_addCode')->findCode($code, $this->_getCompanyId());
        /** @var Mage_Catalog_Model_Product $baseProduct */
        if($additionalCode){
            $code = $additionalCode;
        }
        $baseProduct = $this->findBaseProductByCode($code, $manufacturerId);
        $catalogProductId = $baseProduct;//$baseProduct->getId();
        if(!$catalogProductId) {
            // base product did not found
            $message = sprintf('%s in string %s. Requested code:%s', self::ERROR_BASE_PRODUCT_NOT_FOUND, $this->_getLineNumber(), $code);
            throw new Stableflow_Company_Exception($message);
        }
        $result = array(
            'catalog_product_id' => $catalogProductId,
            'company_product_id' => null,
        );
        // Base product found. Try to find company product
        $companyProductId = $this->findCompanyProduct($catalogProductId, $companyId);
        if ($companyProductId) {
            // company product found
            $result['company_product_id'] = $companyProductId;
        }
        #Varien_Profiler::stop(__METHOD__.'/start');
        return $result;
    }

    /**
     * @param string $code
     * @param int $manufacturerId
     * @return Mage_Catalog_Model_Resource_Product
     */
    public function findBaseProductByCode($code, $manufacturerId)
    {
        static $mfCodeAttribute = null;
        if(!$mfCodeAttribute){
            $mfCodeAttribute = Mage::getModel('eav/entity_attribute')
                ->loadByCode(Mage_Catalog_Model_Product::ENTITY, self::MANUFACTURER_CODE_ATTRIBUTE);
        }
        static $mfNameAttribute = null;
        if(!$mfNameAttribute){
            $mfNameAttribute = Mage::getModel('eav/entity_attribute')
                ->loadByCode(Mage_Catalog_Model_Product::ENTITY, self::MANUFACTURER_ATTRIBUTE);
        }
        static $productEntityTable = null;

        if (!$productEntityTable) {
            $productEntityTable = Mage::getResourceModel('company/product')->getEntityTable();
        }
        $query1 = $this->_connection->select()
            ->from($mfCodeAttribute->getBackend()->getTable(),array('entity_id'))
            ->where('attribute_id = ?', $mfCodeAttribute->getId())
            ->where('value = ?', $code);
        $entityIds = $this->_connection->fetchAll($query1);
        if(!$entityIds){
            return false;
        }
        $query3 = $this->_connection->select()
            ->from(array('main_table' => $productEntityTable), array('entity_id'))
            ->joinLeft(
                array("mfn" => $mfNameAttribute->getBackend()->getTable()),
                "main_table.entity_id = mfn.entity_id",
                array('')
            )
            ->where('main_table.entity_id IN (?)', $entityIds)
            ->where('mfn.value = ?', $manufacturerId)
            ->where('mfn.attribute_id = ?', $mfNameAttribute->getId());
//        $query3 = $this->_connection->select()
//            ->from($productEntityTable, array('entity_id'))
//            ->joinLeft(
//                array("mfn" => $mfNameAttribute->getBackend()->getTable()),
//                $productEntityTable.".entity_id = mfn.entity_id",
//                array('')
//            )
//            ->joinLeft(
//                array("mfc" => $mfCodeAttribute->getBackend()->getTable()),
//                $productEntityTable.".entity_id = mfc.entity_id",
//                array('')
//            )
//            ->where('mfn.value = ?', $manufacturerId)
//            ->where('mfn.attribute_id = ?', $mfNameAttribute->getId())
//            ->where('mfc.value = ?', $code)
//            ->where('mfc.attribute_id = ?', $mfCodeAttribute->getId());
        $productId = $this->_connection->fetchOne($query3);

        return $productId;

//        return Mage::getResourceModel('catalog/product_collection')
//            ->addAttributeToFilter($mfNameAttribute, array('eq' => $manufacturerId))
//            ->addAttributeToFilter($mfCodeAttribute, array('eq' => $code))
//            ->addAttributeToSelect(array('entity_id',self::MANUFACTURER_CODE_ATTRIBUTE , self::MANUFACTURER_ATTRIBUTE))
//            ->initCache(Mage::app()->getCache(),'parser_catalog_product_collection',array('SOME_TAGS'))
//            ->getFirstItem();
    }

    /**
     * @param $catalogProductId
     * @param $companyId
     * @return int $productId product Id
     */
    public function findCompanyProduct($catalogProductId, $companyId)
    {
        static $entityTable = null;

        if (!$entityTable) {
            $entityTable = Mage::getResourceModel('company/product')->getEntityTable();
        }
        $productId = $this->_connection->fetchOne($this->_connection->select()
            ->from($entityTable, array('entity_id'))
            ->where('catalog_product_id = ?', $catalogProductId)
            ->where('company_id = ?', $companyId)
        );
        return $productId;

//        return Mage::getResourceModel('company/product_collection')
//            ->addAttributeToFilter('catalog_product_id', $catalogProductId)
//            ->addAttributeToFilter('company_id', $companyId)
//            ->addAttributeToSelect(array('entity_id', 'catalog_product_id', 'company_id'))
//            ->initCache(Mage::app()->getCache(),'parser_company_product_collection',array('SOME_TAGS'))
//            ->getFirstItem();
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

    /**
     * EAV entity type code getter.
     *
     * @abstract
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'company_product';
    }

    /**
     * Retrieve pattern for time formatting
     *
     * @return string
     */
    protected function _getStrftimeFormat()
    {
        return Varien_Date::convertZendToStrftime(Varien_Date::DATETIME_INTERNAL_FORMAT, true, true);
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