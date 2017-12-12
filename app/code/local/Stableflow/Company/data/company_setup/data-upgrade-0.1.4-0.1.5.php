<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 7/28/17
 * Time: 2:06 PM
 */
?>

<?php
/** @var  $installer Stableflow_Company_Model_Resource_Setup*/
$installer = $this;

$installer->getConnection()
    ->insert($installer->getTable('company/price_type'), array(
        'company_id' => 1,
        'description' => 'Internal price. Type'
    ));
$installer->getConnection()->insert($installer->getTable('company/price_type'), array(
        'company_id' => 1,
        'description' => 'Whole price. Type'
    ));
$installer->getConnection()->insert($installer->getTable('company/parser_config'), array(
        'price_type_id' => 1,
    ));
$installer->getConnection()->insert($installer->getTable('company/parser_config'), array(
        'price_type_id' => 2,
    ));