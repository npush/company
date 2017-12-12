<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/20/17
 * Time: 12:40 PM
 */
class Stableflow_Company_Model_Parser_Log_Message
{
    const MESSAGE_ERROR     = 'error'; //1;
    const MESSAGE_WARNING   = 'warning'; //2;
    const MESSAGE_SUCCESS   = 'success'; //3;

    protected function _factory($type, $statusCode, $rowData, $processData)
    {
        switch (strtolower($type)) {
            case self::MESSAGE_ERROR :
                $message = new Stableflow_Company_Model_Parser_Log_Message_Error($type, $statusCode, $rowData, $processData);
                break;
            case self::MESSAGE_WARNING :
                $message = new Stableflow_Company_Model_Parser_Log_Message_Warning($type, $statusCode, $rowData, $processData);
                break;
            case self::MESSAGE_SUCCESS :
                $message = new Stableflow_Company_Model_Parser_Log_Message_Success($type, $statusCode, $rowData, $processData);
                break;
            default:
                $message = new Stableflow_Company_Model_Parser_Log_Message_Success($type, $statusCode, $rowData, $processData);
                break;
        }
        return $message;
    }

    /**
     * Error message instance
     * @param $statusCode string
     * @param $rowData array
     * @param $processData array
     * @return Stableflow_Company_Model_Parser_Log_Message_Abstract
     */
    public function error($statusCode, $rowData, $processData)
    {
        return $this->_factory(self::MESSAGE_ERROR, $statusCode, $rowData, $processData);
    }

    /**
     * Error message instance
     * @param $statusCode string
     * @param $rowData array
     * @param $processData array
     * @return Stableflow_Company_Model_Parser_Log_Message_Abstract
     */
    public function warning($statusCode, $rowData, $processData)
    {
        return $this->_factory(self::MESSAGE_WARNING, $statusCode, $rowData, $processData);
    }

    /**
     * Error message instance
     * @param $statusCode string
     * @param $rowData array
     * @param $processData array
     * @return Stableflow_Company_Model_Parser_Log_Message_Abstract
     */
    public function success($statusCode, $rowData, $processData)
    {
        return $this->_factory(self::MESSAGE_SUCCESS, $statusCode, $rowData, $processData);
    }
}