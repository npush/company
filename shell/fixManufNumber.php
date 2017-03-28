<?php

$mysqli = new mysqli("localhost", "root", "", "rulletka_beta.dev");
if ($mysqli->connect_errno) {
    printf("Connection error: (%s) %s\n", $mysqli->connect_errno, $mysqli->connect_error);
    die();
}

if (!$mysqli->set_charset("utf8")) {
    printf("Charset set error: %s\n", $mysqli->error);
} else {
    printf("Current charset: %s\n", $mysqli->character_set_name());
}

$result = $mysqli->query("SELECT c.value_id, c.value as value FROM `catalog_product_entity_varchar` c WHERE attribute_id = 146;");

if ($result){
	while( $row = $result->fetch_assoc() ){
        $codes = explode('|',$row['value']);
        array_walk($codes, function(&$_value){
            $_value = str_replace('\"', '', $_value);
        });
        $row['value'] = implode('|', $codes);
        print_r($row);
        $mysqli->query("UPDATE `catalog_product_entity_varchar` SET value = '{$row['value']}' WHERE value_id = '{$row['value_id']}'");
    }

    $result->close();
}
