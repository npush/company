<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/27/17
 * Time: 3:48 PM
 */
class Stableflow_Company_Model_Parser extends Stableflow_Company_Model_Parser_Abstract
{

    const MANUFACTURER_ATTRIBUTE = 'manufacturer';

    protected $_companyId;

    protected $_config = null;

    protected $_entityAdapter;

    /**
     * Create instance of entity adapter and returns it.
     *
     * @throws Mage_Core_Exception
     * @return Mage_ImportExport_Model_Import_Entity_Abstract
     */
    protected function _getEntityAdapter()
    {
        if (!$this->_entityAdapter) {
            $validTypes = Mage_ImportExport_Model_Config::getModels(self::CONFIG_KEY_ENTITIES);

            if (isset($validTypes[$this->getEntity()])) {
                try {
                    $this->_entityAdapter = Mage::getModel($validTypes[$this->getEntity()]['model']);
                } catch (Exception $e) {
                    Mage::logException($e);
                    Mage::throwException(
                        Mage::helper('importexport')->__('Invalid entity model')
                    );
                }
                if (!($this->_entityAdapter instanceof Mage_ImportExport_Model_Import_Entity_Abstract)) {
                    Mage::throwException(
                        Mage::helper('importexport')->__('Entity adapter object must be an instance of Mage_ImportExport_Model_Import_Entity_Abstract')
                    );
                }
            } else {
                Mage::throwException(Mage::helper('importexport')->__('Invalid entity'));
            }
            // check for entity codes integrity
            if ($this->getEntity() != $this->_entityAdapter->getEntityTypeCode()) {
                Mage::throwException(
                    Mage::helper('importexport')->__('Input entity code is not equal to entity adapter code')
                );
            }
            $this->_entityAdapter->setParameters($this->getData());
        }
        return $this->_entityAdapter;
    }

    /**
     * Returns source adapter object.
     *
     * @param string $sourceFile Full path to source file
     * @return Mage_ImportExport_Model_Import_Adapter_Abstract
     */
    protected function _getSourceAdapter($sourceFile)
    {
        return Stableflow_Company_Model_Parser_Adapter::findAdapterFor($sourceFile);
    }

    /**
     * Get task status
     *
     * @return int
     */
    public function getStatus()
    {
        if (is_null($this->_getData('status_id'))) {
            $this->setData('status_id', Stableflow_Company_Model_Parser_Status::STATUS_ENABLED);
        }
        return $this->_getData('status_id');
    }

    public function setCompanyId(Stableflow_Company_Model_Company $company)
    {
        $this->_companyId = $company->getId();
    }

    public function getCompanyId()
    {
        return $this->_companyId;
    }

    /**
     * Retrieve tasks collection
     * @param $companyId
     * @return Stableflow_Company_Model_Resource_Parser_Config_Collection
     */
    public function getTasksCollection($companyId)
    {
        $configCollection = Mage::getModel('company/parser_config')->getConfigCollection($companyId);
        $ids = array();
        foreach($configCollection as $config){
            $ids[] = $config->getId();
        }
        return $this->getCollection()
            ->addFieldToFilter('config_id', array('in' => $ids));
    }

    public function loadTask($id)
    {
        return Mage::getModel('company/parser_task')->load($id);
    }

    public function getPriceType(){}

    public function getConfig()
    {
        return $this->_config;
    }

    public function addTaskToQueue($taskId)
    {

    }

    public function getTaskStatus(){}
    public function getQueue(){}

    public function getManufacturers()
    {
        $attribute = Mage::getModel('eav/entity_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, self::MANUFACTURER_ATTRIBUTE);
        $as = $attribute->getSource()->getOptionArray();
        $valuesCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
            ->setAttributeFilter($attribute->getId())
            ->setStoreFilter(0, false);
    }

    public function updatePriceLists()
    {
        Mage::log("Import",null, 'PriceLists.log');
        try{
            $queue = Mage::getModel('company/parser_queue');
            $queue->performQueue();

        }catch (Exception $e){
            Mage::log($e, null, 'PriceLists-exception.log');
        }
    }
}