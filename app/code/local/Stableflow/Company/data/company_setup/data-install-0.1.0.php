<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/7/17
 * Time: 3:04 PM
 */

$installer = $this;

//Fill table review/review_entity
$reviewEntityCodes = array(
    'company',
);
foreach ($reviewEntityCodes as $entityCode) {
    $installer->getConnection()
        ->insert($installer->getTable('review/review_entity'), array('entity_code' => $entityCode));
}

//Fill table rating/rating_entity
$ratingEntityCodes = array(
    'company_review','company'
);
foreach ($ratingEntityCodes as $entityCode) {
    $installer->getConnection()
        ->insert($installer->getTable('rating/rating_entity'), array('entity_code' => $entityCode));
}
