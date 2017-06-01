<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 5/31/17
 * Time: 8:57 PM
 */
?>
<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('smasoft_oneclickorder/order')}
    ADD COLUMN `guest_email` VARCHAR(255) DEFAULT NULL AFTER `comment`,
    ADD COLUMN `guest_name` VARCHAR(255) DEFAULT NULL AFTER `guest_email`;
");

$installer->endSetup();