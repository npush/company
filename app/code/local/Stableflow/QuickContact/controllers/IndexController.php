<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 10/4/17
 * Time: 11:10 AM
 */


class Stableflow_QuickContact_IndexController extends Mage_Core_Controller_Front_Action
{		
    const XML_PATH_EMAIL_RECIPIENT      = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER         = 'contacts/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE       = 'quickcontact/email/template_email';
    const XML_PATH_EMAIL_TEMPLATE_PRICE = 'quickcontact/email/template_email_price';
	
    public function indexAction()
    {
        $this->loadLayout();
        $this->getLayout()->getBlock('quickcontactForm');
        $this->renderLayout();
    }
	    
    public function postAction()
    {			
		$post = $this->getRequest()->getPost();		
		if(!$post) exit;
		$translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
		try {
            $postObject = new Varien_Object();
            $postObject->setData($post);

            $error = false;

            if (!Zend_Validate::is(trim($post['name']) , 'Alpha')) {
                $error = true;
            }

            if (!Zend_Validate::is(trim($post['comment']) , 'NotEmpty')) {
                $error = true;
            }

            if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                $error = true;
            }

            /*if (!Zend_Validate::is(trim($post['telephone']), 'Regex', '/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/')) {
                $error = true;
            }*/

            if (Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                $error = true;
            }

            if(trim($post['comment']) != strip_tags(trim($post['comment']))) {
                $error = true;
            }

            if(preg_match_all(
                "/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/u",
                trim($post['comment'])
                )){
                $error = true;
            }

            if(trim($post['name']) != strip_tags(trim($post['name']))) {
                $error = true;
            }

            if ($error) {
                throw new Exception();
            }

            if (!isset($postObject['telephone']) || strlen($postObject['telephone'])<1) {
                $postObject['telephone'] = '';
            }

            Mage::helper('quickcontact')->logRequest();
               		
				$mailTemplate = Mage::getModel('core/email_template');				
				$mailTemplate->setDesignConfig(array('area' => 'frontend'))
					->setReplyTo($post['email'])
					->sendTransactional(
						Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                        null,
                        array('data' => $postObject)
					);

				if (!$mailTemplate->getSentSuccess()) {					
					echo '<div class="alert alert-danger" role="alert">'.Mage::helper('contacts')->__('Unable to submit your request. Please, try again later.').'</div>';
					exit;
				}				
				$translate->setTranslateInline(true);

                echo '<div class="alert alert-success" role="alert">'.Mage::helper('contacts')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.').'</div>';
			} catch (Exception $e) {
				$translate->setTranslateInline(true);
				echo '<div class="alert alert-danger" role="alert">'.Mage::helper('contacts')->__('Unable to submit your request. Please, try again later.').$e.'</div>';
				exit;
			}		
	}	
}
?>