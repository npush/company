<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/31/17
 * Time: 5:30 PM
 */
class Stableflow_Company_Model_Parser_Config_Settings extends Varien_Object
{
    /**
     * Additional params
     * @var array
     */
    protected $_params = null;

    /**
     * File type (csv, xml, xls, etc...)
     * @var string
     */
    protected $_type = null;

    /**
     * Sheets settings
     * @var array
     */
    protected $_sheets = array();

    protected $_defconf = array(
        'field_map' => array(
            'price'             => null,
            'price_internal'    => null,
            'price_wholesale'   => null,
            'item_price'        => null,
            'box'               => null,
            'name'              => null,
            'code'              => null,
            'qty_in_stock'      => null,
        ),
        'settings' => array(
            'header_row'        => null,
            'start_row'         => null,
            'manufacturer'      => null,
        ),
        'settings_currency' => array(
            'currency'          => null,
            'change_currency'   => null,
        )
    );

    /**
     * current sheet settings
     * @var array
     */
    protected $_sheetSettings;

    /**
     * Current sheet Num. Default sheet 0
     * @var int
     */
    protected $_currentSheet = 0;


    public function __construct()
    {
        $args = func_get_args();
        $this->setSettings($args[0]);
        $this->_construct();
    }

    public function getType()
    {
        return $this->_type;
    }

    public function getCurrentSheetNum()
    {
        return $this->_currentSheet;
    }

    /**
     * Set Current sheet
     * @param int $sheetNum
     * @return Stableflow_Company_Model_Parser_Config_Settings
     */
    public function setCurrentSheetNum($sheetNum)
    {
        if(!array_key_exists($sheetNum, $this->_sheets)){
            return false;
        }
        $this->_currentSheet = $sheetNum;
        $this->getSheetSettings();
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getSheetSettings()
    {
        $this->_sheetSettings = $this->_sheets[$this->_currentSheet];
        return $this->_sheetSettings;
    }

    public function setSettings($settings)
    {
        if(is_array($settings)) {
            $this->_currentSheet = $settings[0]['index'];
            $this->_type = $settings[0]['type'];
            foreach($settings as $_tab){
                unset($_tab['type']);
                $index = $_tab['index'];
                unset($_tab['index']);
                $this->_sheets[$index] = array_merge($this->_defconf, $_tab);
            }
            $this->_sheetSettings = $this->_sheets[$this->_currentSheet];
        }
        return $this;
    }

    public function getSheetsCont()
    {
        return count($this->_sheets);
    }

    public function getSheetsNumbers()
    {
        return array_keys($this->_sheets);
    }

    public function getAllSheets()
    {
        return $this->_sheets;
    }

    /**
     * @return array | bool
     */
    public function getFieldMap()
    {
        return $this->_sheetSettings['field_map'];
    }

    public function getStartRow()
    {
        return $this->_sheetSettings['settings']['start_row'];
    }

    public function getHeaderRow()
    {
         return $this->_sheetSettings['settings']['header_row'];
    }

    public function getCurrency()
    {
        return array_keys($this->_sheetSettings['settings_currency'], array('currency', 'change_currency'));
    }

    public function getManufacturer()
    {
        return $this->_sheetSettings['settings']['manufacturer'];
    }
}