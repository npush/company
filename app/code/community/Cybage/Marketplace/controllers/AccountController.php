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

require_once 'Mage/Customer/controllers/AccountController.php';
class Cybage_Marketplace_AccountController extends Mage_Customer_AccountController
{
    /**
     * Create customer account action
     */
    public function createPostAction()
    {
        $session = $this->_getSession();
        if ($session->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }

        $isMarketplaceEnabled = Mage::Helper("marketplace")->isMarketplaceEnabled();
        if($isMarketplaceEnabled == false) {
            return parent::createPostAction();
        }

        $session->setEscapeMessages(true); // prevent XSS injection in user input
        if (!$this->getRequest()->isPost()) {
            $errUrl = $this->_getUrl('*/*/create', array('_secure' => true));
            $this->_redirectError($errUrl);
            return;
        }

        $customer = $this->_getCustomer();

        try {
            $errors = $this->_getCustomerErrors($customer);
            if($this->getRequest()->getParam('check_seller_form')) {
                $customer->setCompanyLocality($this->getRequest()->getPost('company_locality'));
                $customer->setCompanyName($this->getRequest()->getPost('company_name'));
                $customerErrors = Mage::getModel('marketplace/customer')->customValidate($customer);
            }

            if (is_array($customerErrors)) {
                $errors = array_merge($customerErrors, $errors);
            }

            if (empty($errors)) {
                $customer->cleanPasswordsValidationData();

                // saving seller information
                if($this->getRequest()->getParam('check_seller_form')) {
                    $customerId = $customer->getEntityId();
                    /******************** company banner upload code ******************************** */
                    if (isset($_FILES['company_banner']['name']) && $_FILES['company_banner']['name'] != '') {
                        $fileName = $_FILES['company_banner']['name'];
                        $fieldName = 'company_banner';
                        $companyBanner = $this->_uploadImage($fileName,$fieldName,$customerId);
                        $customer->setCompanyBanner($companyBanner);
                    }
                    /******************* end of company banner code ******************************** */

                    /******************** company logo upload code ******************************** */
                    if (isset($_FILES['company_logo']['name']) && $_FILES['company_logo']['name'] != '') {
                        $fileName = $_FILES['company_logo']['name'];
                        $fieldName = 'company_logo';
                        $companyLogo = $this->_uploadImage($fileName,$fieldName,$customerId);
                        $customer->setCompanyLogo($companyLogo);
                    }
                    /******************* end of company logo code ******************************** */

                    $customer->setCompanyLocality($this->getRequest()->getPost('company_locality'));
                    $customer->setCompanyName($this->getRequest()->getPost('company_name'));
                    $customer->setCompanyDescription($this->getRequest()->getPost('company_description'));
                    $customer->setSellerSubscriber(1);

                    // Auto approval of seller check
                    if (Mage::getStoreConfig('marketplace/marketplace/auto_approval_seller')) {
                        $customer->setStatus(Mage::getStoreConfig('marketplace/status/approved'));
                    } else {
                        $customer->setStatus(Mage::getStoreConfig('marketplace/status/pending'));
                    }
                } else {
                    $customer->setSellerSubscriber(0);
                }

                $customer->save();
                $this->_dispatchRegisterSuccess($customer);
                $this->_successProcessRegistration($customer);
                return;
            } else {
                $this->_addSessionError($errors);
            }
        } catch (Mage_Core_Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost());
            if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) {
                $url = $this->_getUrl('customer/account/forgotpassword');
                $message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);
                $session->setEscapeMessages(false);
            } else {
                $message = $e->getMessage();
            }
            $session->addError($message);
        } catch (Exception $e) {
            $session->setCustomerFormData($this->getRequest()->getPost())
                ->addException($e, $this->__('Cannot save the customer.'));
        }
        $errUrl = $this->_getUrl('*/*/create', array('_secure' => true));
        $this->_redirectError($errUrl);
    }
    
    /**
    * Image upload method
    *
    */
    protected function _uploadImage($fileName,$fieldName,$customerId)
    {
        try 
        {   
            /* Starting upload */
                                          
            $uploader = new Varien_File_Uploader($fieldName);
            // Any extention would work
            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
            $uploader->setAllowRenameFiles(true);                   
            $uploader->setFilesDispersion(false);
            $checkPath = Mage::getBaseDir() . DS . 'media' . DS . 'marketplace/'.$customerId;
            $createPath = Mage::getBaseDir() . DS . 'media';            
            if(!file_exists($checkPath))
            {  
               mkdir($createPath ."/" . "marketplace/".$customerId, 0777);                   
            } 
            
            // We set media as the upload dir
            $path = Mage::getBaseDir() . DS . 'media' . DS . 'marketplace/'.$customerId;              
            $result = $uploader->save($path,$fileName);                                  
        } catch (Exception $e) 
        { 
            $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                               ->addException($e, $this->__('Cannot save the image. Please check the owner and folder permission.'));                              
        }

        //this way the name is saved in DB
        $companyBanner = 'marketplace/'.$customerId.'/'.$result['file'];
        if($fieldName == 'company_banner')
        {
            $width = Mage::getStoreConfig('marketplace/marketplace/default_width',Mage::app()->getStore());
            $height = Mage::getStoreConfig('marketplace/marketplace/default_height',Mage::app()->getStore());
            //$resizedURL = $this->_getresizeImg($fileName, $width, $height, $path, $customerId);
            //return $resizedURL;
        }
        else if($fieldName == 'company_logo')
        {
            $width = Mage::getStoreConfig('marketplace/marketplace/default_logo_width',Mage::app()->getStore());
            $height = Mage::getStoreConfig('marketplace/marketplace/default_logo_width',Mage::app()->getStore());
            
        }
        
        $resizedURL = $this->_getresizeImg($fileName, $width, $height, $path, $customerId);
        return $resizedURL;
    }

    /**
     * Image resize code (700 X 100). 
     */
    protected function _getresizeImg($fileName, $width, $height, $path, $sid)
    {
        $folderURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA). DS . 'marketplace/'.$sid;
        $imageURL = $folderURL .'/'. $fileName;

        $basePath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA). DS . 'marketplace/'.$sid.'/'.$fileName;
        $newPath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA). DS . 'marketplace/'.$sid.'/'.$fileName;
        //if width empty then return original size image's URL
        if ($width != '') 
        {
        //if image has already resized then just return URL
        if (file_exists($basePath) && is_file($basePath)) {
            $imageObj = new Varien_Image($basePath);
            $imageObj->constrainOnly(TRUE);
            $imageObj->keepAspectRatio(FALSE);
            $imageObj->keepFrame(FALSE);
            $imageObj->resize($width, $height);
            $imageObj->save($newPath);
        }
        $resizedURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA). 'marketplace/'.$sid.'/'.$fileName;
        }  else {
           $resizedURL = $imageURL;
        }
        
     return $resizedURL;
    }
   
    public function editPostAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/edit');
        }

        if ($this->getRequest()->isPost()) {
            /** @var $customer Mage_Customer_Model_Customer */
            $customer = $this->_getSession()->getCustomer();

            /** @var $customerForm Mage_Customer_Model_Form */
            $customerForm = Mage::getModel('customer/form');
            $customerForm->setFormCode('customer_account_edit')
                ->setEntity($customer);

            $customerData = $customerForm->extractData($this->getRequest());

            $errors = array();
            $customerErrors = $customerForm->validateData($customerData);
            if ($customerErrors !== true) {
                $errors = array_merge($customerErrors, $errors);
            } else {
                $customerForm->compactData($customerData);
                $errors = array();

                // If password change was requested then add it to common validation scheme
                if ($this->getRequest()->getParam('change_password')) {
                    $currPass   = $this->getRequest()->getPost('current_password');
                    $newPass    = $this->getRequest()->getPost('password');
                    $confPass   = $this->getRequest()->getPost('confirmation');

                    $oldPass = $this->_getSession()->getCustomer()->getPasswordHash();
                    if (Mage::helper('core/string')->strpos($oldPass, ':')) {
                        list($_salt, $salt) = explode(':', $oldPass);
                    } else {
                        $salt = false;
                    }

                    if ($customer->hashPassword($currPass, $salt) == $oldPass) {
                        if (strlen($newPass)) {
                            /**
                             * Set entered password and its confirmation - they
                             * will be validated later to match each other and be of right length
                             */
                            $customer->setPassword($newPass);

                            // This is used for compatible with 1.9.1.0
                            if (version_compare(Mage::getVersion(), '1.9.1.0', '>='))
                                $customer->setPasswordConfirmation($confPass);
                            else 
                                $customer->setConfirmation($confPass);
                        } else {
                            $errors[] = $this->__('New password field cannot be empty.');
                        }
                    } else {
                        $errors[] = $this->__('Invalid current password');
                    }
                }
                $validationFlag = 0; 
                
                // saving seller information
                if($this->getRequest()->getParam('check_seller_form'))
                {
                    $customerId = Mage::getSingleton('customer/session')->getId();
                    
                    /******************** company banner upload code ******************************** */
                    if (isset($_FILES['company_banner']['name']) && $_FILES['company_banner']['name'] != '') 
                    {
                        $dimensions = getimagesize($_FILES["company_banner"]["tmp_name"]);
                        $width  = Mage::getStoreConfig('marketplace/marketplace/default_width');
                        $height = Mage::getStoreConfig('marketplace/marketplace/default_height');
                        
                        if(is_array($dimensions) && !empty($dimensions))
                        {
                            /**if($dimensions[0] > $width || $dimensions[1] > $height)
                            {
                                $errors[] = $this->__('Please upload company banner within specified dimensions.');
                            }
                            else**/                            
                            {
                                $fileName = $_FILES['company_banner']['name'];
                                $fieldName = 'company_banner';
                                $companyBanner = $this->_uploadImage($fileName,$fieldName,$customerId);
                                $customer->setCompanyBanner($companyBanner); 
                            }
                        }
                        else
                        {   
                            $errors[] = $this->__('Please upload valid file for company banner.');        
                        }
                    } 
                    /******************* end of company banner code ******************************** */

                    /******************** company logo upload code ******************************** */
                    if (isset($_FILES['company_logo']['name']) && $_FILES['company_logo']['name'] != '') 
                    {
                        $dimensions = getimagesize($_FILES["company_logo"]["tmp_name"]);
                        $width  = Mage::getStoreConfig('marketplace/marketplace/default_logo_width');
                        $height = Mage::getStoreConfig('marketplace/marketplace/default_logo_height');
                    
                        if(is_array($dimensions) && !empty($dimensions))
                        {
                            /**if($dimensions[0] > $width || $dimensions[1] > $height)
                            {
                                $errors[] = $this->__('Please upload company logo within specified dimensions.');
                            }
                            else**/
                            {
                                $fileName = $_FILES['company_logo']['name'];
                                $fieldName = 'company_logo';
                                $companyLogo = $this->_uploadImage($fileName,$fieldName,$customerId);
                                $customer->setCompanyLogo($companyLogo); 
                            }
                        }
                        else
                        {   
                            $errors[] = $this->__('Please upload valid file for company logo.');        
                        }
                    }
                    /******************* end of company logo code ******************************** */			   
                    
                    $customer->setCompanyLocality($this->getRequest()->getPost('company_locality'));
                    $customer->setCompanyName($this->getRequest()->getPost('company_name'));
                    $customer->setCompanyDescription($this->getRequest()->getPost('company_description'));		   
                    $customer->setSellerSubscriber(1);
                    
                    // Auto approval of seller check
                    if (Mage::getStoreConfig('marketplace/marketplace/auto_approval_seller')) {
                        $customer->setStatus(Mage::getStoreConfig('marketplace/status/approved'));
                    } else {
                        $customer->setStatus(Mage::getStoreConfig('marketplace/status/pending'));
                    }

                    $validationFlag = 1;
                    $sellerSubscriber = $this->getRequest()->getPost('is_seller_subscribe');
                    if(empty($sellerSubscriber))
                    {                        
                        $url = $this->_welcomeCustomer($customer);                        
                    }
                }
                else
                {
                   $sellerSubscriber = $this->getRequest()->getPost('is_seller_subscribe');
                   if(empty($sellerSubscriber))
                   {                   
                    $customer->setSellerSubscriber(0);
                   }
                }

                // Validate account and compose list of errors if any
                if($validationFlag == 1)
                {
                  $customerErrors = Mage::getModel('marketplace/customer')->customValidate($customer);
                }
                else
                {
                  $customerErrors = $customer->validate();
                }

                if (is_array($customerErrors)) {
                    $errors = array_merge($errors, $customerErrors);
                }
            }

            if (!empty($errors)) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
                foreach ($errors as $message) {
                    $this->_getSession()->addError($message);
                }
                $this->_redirect('*/*/edit');
                return $this;
            }

            try {
                $customer->setConfirmation(null);
                $customer->save();
                $this->_getSession()->setCustomer($customer)
                    ->addSuccess($this->__('The account information has been saved.'));

                $this->_redirect('customer/account');
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                    ->addException($e, $this->__('Cannot save the customer.'));
            }
        }

        $this->_redirect('*/*/edit');
    } 
    
    public function marketplaceOrdersAction()
    {
         $this->loadLayout();
         $this->renderLayout();
    }

    public function viewOrderAction(){
        $this->_validateCustomerLogin();
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     *    validate Customer Login and redirect previous page 
     * */
    protected function _validateCustomerLogin() {
        $session = Mage::getSingleton('customer/session');
        if (!$session->isLoggedIn()) {
            $session->setAfterAuthUrl(Mage::helper('core/url')->getCurrentUrl());
            $session->setBeforeAuthUrl(Mage::helper('core/url')->getCurrentUrl());
            $this->_redirect('customer/account/login/');
            return $this;
        }elseif(!Mage::helper('marketplace')->isMarketplaceActiveSellar()){
            $this->_redirect('customer/account/');
        }
    }
}
