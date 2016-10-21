<?php
/**
 * Cybage Marketplace Plugin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * It is available on the World Wide Web at:
 * http://opensource.org/licenses/osl-3.0.php
 * If you are unable to access it on the World Wide Web, please send an email
 * To: Support_Magento@cybage.com.  We will send you a copy of the source file.
 *
 * @category   Marketplace Plugin
 * @package    Cybage_Marketplace
 * @copyright  Copyright (c) 2014 Cybage Software Pvt. Ltd., India
 *             http://www.cybage.com/pages/centers-of-excellence/ecommerce/ecommerce.aspx
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Cybage Software Pvt. Ltd. <Support_Magento@cybage.com>
 */

class Cybage_Marketplace_Model_Customer extends Mage_Customer_Model_Customer
{
    const XML_PATH_REGISTER_SELLER_EMAIL_TEMPLATE = 'marketplace/seller/email_template';
    
    public function customValidate($customer)
    {
        $errors = array();
        // custom validation for seller profile
         if (!Zend_Validate::is( trim($customer->getCompanyLocality()) , 'NotEmpty')) {
            $errors[] = Mage::helper('customer')->__('The company locality cannot be empty.');
        }

        if (!Zend_Validate::is( trim($customer->getCompanyName()) , 'NotEmpty')) {
            $errors[] = Mage::helper('customer')->__('The company name cannot be empty.');
        }
        // end of custom validation for seller profile

        if (empty($errors)) {
            return true;
        }
        return $errors;
    }

    /**
     * Send email with new account related information
     *
     * @param string $type
     * @param string $backUrl
     * @param string $storeId
     * @throws Mage_Core_Exception
     * @return Mage_Customer_Model_Customer
     */
    public function sendNewAccountEmail($type = 'registered', $backUrl = '', $storeId = '0')
    {
        if(Mage::app()->getRequest()->getParam('check_seller_form'))
        {            
            $types = array(
                'registered'   => self::XML_PATH_REGISTER_SELLER_EMAIL_TEMPLATE, // welcome email, when confirmation is disabled
                'confirmed'    => self::XML_PATH_CONFIRMED_EMAIL_TEMPLATE, // welcome email, when confirmation is enabled
                'confirmation' => self::XML_PATH_CONFIRM_EMAIL_TEMPLATE, // email with confirmation link
            );
        
         
            if (!isset($types[$type])) {
                Mage::throwException(Mage::helper('customer')->__('Wrong transactional account email type'));
            }

            if (!$storeId) {
                $storeId = $this->_getWebsiteStoreId($this->getSendemailStoreId());
            }

            $this->_sendEmailTemplate($types[$type], self::XML_PATH_REGISTER_EMAIL_IDENTITY,
                array('customer' => $this, 'back_url' => $backUrl), $storeId);

            return $this;
        }
        return parent::sendNewAccountEmail($type = 'registered', $backUrl = '', $storeId = '0');
    }    
}
