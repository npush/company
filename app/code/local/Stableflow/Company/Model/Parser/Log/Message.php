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

    protected function _factory($data, $type, $text)
    {
        switch (strtolower($type)) {
            case self::MESSAGE_ERROR :
                $message = new Stableflow_Company_Model_Parser_Log_Message_Error($data, $text);
                break;
            case self::MESSAGE_WARNING :
                $message = new Stableflow_Company_Model_Parser_Log_Message_Warning($data, $text);
                break;
            case self::MESSAGE_SUCCESS :
                $message = new Stableflow_Company_Model_Parser_Log_Message_Success($data, $text);
                break;
            default:
                $message = new Stableflow_Company_Model_Parser_Log_Message_Success($data, $text);
                break;
        }
        return $message;
    }

    /**
     * Error message instance
     * @param $data array
     * @param $text string
     * @return Stableflow_Company_Model_Parser_Log_Message_Abstract
     */
    public function error($data, $text = self::MESSAGE_ERROR)
    {
        return $this->_factory($data, self::MESSAGE_ERROR, $text);
    }

    /**
     * Error message instance
     * @param $data array
     * @param $text string
     * @return Stableflow_Company_Model_Parser_Log_Message_Abstract
     */
    public function warning($data, $text = self::MESSAGE_WARNING)
    {
        return $this->_factory($data, self::MESSAGE_WARNING, $text);
    }

    /**
     * Error message instance
     * @param $data array
     * @param $text string
     * @return Stableflow_Company_Model_Parser_Log_Message_Abstract
     */
    public function success($data, $text = self::MESSAGE_SUCCESS)
    {
        return $this->_factory($data, self::MESSAGE_SUCCESS, $text);
    }
}