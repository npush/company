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
     * @var array
     */
    protected $_sheets = null;


    protected $_currentSheet = 0;

    protected $_sheetSettings;

    protected $_defaultConfig = array(
        'type' => null,
        'sheets' => array(
            0 => array(
                'field_map' => array(
                    'price'             => null,
                    'item_price'        => null,
                    'box'               => null,
                    'name'              => null,
                    'code'              => null,
                    'qty_in_stock'      => null,
                ),
                'settings' => array(
                    'header_row'        => null,
                    'start_row'         => null,
                ),
                'settings_currency' => array(
                    'currency'          => null,
                    'change_currency'   => null,
                )
            ),
        ),
        'params' => array(),
    );

    /**
     * Set default sheet 0
     */
    protected function _construct()
    {
        if(!count($this->_data)){
            $this->setData($this->_defaultConfig);
        }
        $this->getSheets();
        $this->setCurrentSheet($this->_currentSheet);
    }

    public function getType()
    {
        return $this->getData('type');
    }

    public function getCurrentSheet()
    {
        return $this->_currentSheet;
    }

    /**
     * Set Current sheet
     * @param int $sheetNum
     * @return Stableflow_Company_Model_Parser_Config_Settings
     */
    public function setCurrentSheet($sheetNum)
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
        $this->_sheetSettings = $this->_sheets[$this->_currentSheet]['settings'];
        return $this->_sheetSettings;
    }

    /**
     * @return array | bool
     */
    public function getFieldMap()
    {
        return $this->_sheets[$this->_currentSheet]['field_map'];
    }

    public function getStartRow()
    {
        return $this->_sheetSettings['start_row'];
    }

    public function getHeaderRow()
    {
         return $this->_sheetSettings['header_row'];
    }

    public function getSheetsCont()
    {
        return count($this->_sheets);
    }

    public function getSheets()
    {
        if(is_null($this->_sheets)){
            $this->_sheets = $this->getData('sheets');
        }
        return $this->_sheets;
    }

    public function getCurrency()
    {
        return array_keys($this->_sheetSettings, array('currency', 'change_currency'));
    }

}