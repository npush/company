<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 5/16/17
 * Time: 4:35 PM
 */
?>

<?php
$installer = $this;

$installer->getConnection()
    ->insert($installer->getTable('customer/customer_group'), array(
        'customer_group_code' => 'Company_Owner',
        'tax_class_id' => 3
    ));
