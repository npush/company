<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/20/17
 * Time: 12:44 PM
 */
class Stableflow_Company_Model_Parser_Log_Message_Abstract
{
    /**
     * Message type ERROR | SUCCESS | WARNING
     * @var string
     */
    protected $_type;

    protected $_statusCode;

    protected $_data = array();

    /**
     * string content
     * @var array
     */
    protected $_content;

    protected $_identifier;


    public function __construct($type, $statusCode, $rowData, $processData)
    {
        $this->_identifier = Varien_Date::now();
        $this->_statusCode = $statusCode;
        $this->_type = $type;
        $this->_data = $processData;
        $this->_content = $rowData;
    }

    /**
     * Message type
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Get message identifier
     *
     *  @return string
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }

    /**
     * Get raw string content
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }

    public function getSheetName()
    {
        $lineNum = explode(':', $this->_data['line_num']);
        return $lineNum[0];
    }

    public function getLineNumber()
    {
        $lineNum = explode(':', $this->_data['line_num']);
        return $lineNum[1];
    }

    public function getProcessedData()
    {
        return $this->_data;
    }


    public function _debugInfo()
    {
        $out = sprintf("Message: %s. In Sheet ID:%d : Row Num:%d. ManufCode: %s. Company prod Id: %d. Catalog prod Id: %d.\n",
            $this->getType(), $this->getSheetName(), $this->getLineNumber(), $this->_data['code'], $this->_data['company_product_id'], $this->_data['catalog_product_id']
        );

        return $out;
    }
}