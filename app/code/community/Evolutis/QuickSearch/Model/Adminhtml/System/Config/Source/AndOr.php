 <?php
 
 /**
  * Evolutis_QuickSearch
  *
  * NOTICE OF LICENSE
  *
  * This source file is subject to the Open Software License (OSL 3.0)
  * that is bundled with this package in the file LICENSE.txt.
  * It is also available through the world-wide-web at this URL:
  * http://opensource.org/licenses/osl-3.0.php
  * If you did not receive a copy of the license and are unable to
  * obtain it through the world-wide-web, please send an email
  * to contact@evolutis.fr so we can send you a copy immediately.
  *
  * @category    QuickSearch
  * @package     Evolutis_QuickSearch
  * @copyright  Copyright (c) 2001-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
  * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
  */
 
class Evolutis_QuickSearch_Model_Adminhtml_System_Config_Source_AndOr {
	
	/*
	 * Cette fonction est appelé grâce à system.xml
	 * Elle donne les valeurs au select de la page parametre du module.
	 */
	public function toOptionArray() {
		$helper = Mage::helper('evolutis_quicksearch');
		
		return array(
				array('value' => '0', 'label' => $helper->__('OR (at least one word must match)')),
				array('value' => '1', 'label' => $helper->__('AND (all words must match)'))
		);
	}
}