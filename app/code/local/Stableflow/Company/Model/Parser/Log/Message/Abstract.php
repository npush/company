<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/20/17
 * Time: 12:44 PM
 */
class Stableflow_Company_Model_Parser_Log_Message_Abstract implements ArrayAccess
{

    protected $_messages = array();

    /**
     * Message type ERROR | SUCCESS | WARNING
     * @var string
     */
    protected $_type;

    /**
     * Task ID
     * @var int
     */
    protected $_taskId;

    /**
     * Line number
     * @var int
     */
    protected $_lineNum;


    /**
     * Sheet Id
     * @var int
     */
    protected $_sheetId;

    /**
     * @var array
     */
    protected $_content;

    protected $_companyProductId;
    protected $_catalogProductId;

    protected $_code;

    protected $_identifier;

    public function __construct($type, $data)
    {
        $this->_messages[] = $data;
        $this->_type = $type;

        $this->_taskId = $data['task_id'];
        list($this->_sheetId, $this->_lineNum) = explode(':', $data['line_num']);
        //$this->_content = $data->getContent();
        $this->_companyProductId = $data['company_product_id'];
        $this->_catalogProductId = $data['catalog_product_id'];

        $this->_code = $data['code'];
    }

    public function offsetSet($offset, $message) {
        if (is_null($offset)) {
            $this->_messages[] = $message;
        } else {
            $this->_messages[$offset] = $message;
        }
    }

    public function offsetExists($offset) {
        return isset($this->_messages[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->_messages[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->_messages[$offset]) ? $this->_messages[$offset] : null;
    }


    public function getCode()
    {
        return $this->_code;
    }

    /**
     * Get code message
     * @return string
     */
    public function getText()
    {
        return json_encode($this->_content);
    }

    public function getType()
    {
        return $this->_type;
    }

    public function toString()
    {
        $out = $this->getType().': '.$this->getText();
        return $out;
    }

    public function getProductReference()
    {
        return array(
            'catalog_product_id' => $this->_catalogProductId,
            'company_product_id' => $this->_companyProductId
        );
    }

    /**
     * Set message identifier
     *
     * @param string $id
     * @return Stableflow_Company_Model_Parser_Log_Message_Abstract
     */
    public function setIdentifier($id)
    {
        $this->_identifier = $id;
        return $this;
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

    public function _debugInfo()
    {
        $out = sprintf("Message: %s. In Sheet ID:%d : Row Num:%d. ManufCode: %s. Company prod Id: %d. Catalog prod Id: %d.\n",
            $this->getType(), $this->_sheetId, $this->_lineNum, $this->getCode(), $this->_companyProductId, $this->_catalogProductId
        );

        return $out;
    }
}