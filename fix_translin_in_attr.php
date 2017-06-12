<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 6/12/17
 * Time: 12:06 PM
 */

require_once('app/Mage.php');
Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));


$repaceValues = array(
    '&degC'         => '&deg;C',
    '&degС'         => '&deg;C',        // Russian "C"
    '&amp;degC'     => '&deg;C',
    '&amp;degС'     => '&deg;C',        // Russian "C"
    '&amp;deg;C'    => '&deg;C',
    '&amp;deg;С'    => '&deg;C',       // Russian "C"
    '°C'            => '&deg;C',
    '°С'            => '&deg;C',        // Russian "C"
    '̊С'            => '&deg;C',
    '&sup2.'        => '&sup2;.',
    '&sup2 '        => '&sup2; ',
    '&sup2)'        => '&sup2;)',
    '&amp;sup2.'    => '&sup2;.',
    '&amp;sup2,'    => '&sup2;,',
    '&amp;sup2 '    => '&sup2; ',
    '&amp;sup2)'    => '&sup2;)',
    '&sup3.'        => '&sup3;.',
    '&sup3 '        => '&sup3; ',
    '&sup3)'        => '&sup3;)',
    '&amp;sup3.'    => '&sup3;.',
    '&amp;sup3,'    => '&sup3;,',
    '&amp;sup3 '    => '&sup3; ',
    '&amp;sup3)'    => '&sup3;)',
    //--
    /*'²'             => '&sup3;)',
    '³'             => '&sup3;)',
    '°'             => '&deg;',
    '¼'             => '&frac14;',
    '½'             => '&frac12;',
    '¾'             => '&frac34;',
    'Ø'             => '&#216;',
    'ø'             => '&#248;',*/
);

/** @var  $resource Mage_Core_Model_Resource*/
$resource = Mage::getSingleton('core/resource');
/** @var $readConnection Varien_Db_Adapter_Pdo_Mysql */
$readConnection = $resource->getConnection('core_read');
/** @var  $writeConnection Varien_Db_Adapter_Pdo_Mysql */
$writeConnection = $resource->getConnection('core_write');

$productTable = 'catalog_product_entity_text';

/*$query = 'SELECT `value_id`,  `value` FROM ' . $productTable . ' WHERE value LIKE "%deg%" OR value LIKE "%&sup2%" OR value LIKE "%°С%"' ;
$data = $readConnection->fetchPairs($query);
foreach($data as $value_id => $value){
    $result = str_replace(array_keys($repaceValues), $repaceValues, $value);
    try{
        if($result != $value){
            $witeQuery = 'UPDATE ' . $productTable . ' SET `value` = \'' . $result . '\' WHERE value_id = '. $value_id;
            $writeConnection->query($witeQuery);
            $result = null;
        }
    }catch (Exception $e){
        Mage::log($e, null, 'fix.log');
    }

}*/
$count = 0;
$productTable = 'catalog_product_entity_varchar';
$attrNameId = 71;
$query = 'SELECT `value`, `value_id` FROM ' . $productTable . ' WHERE `attribute_id` = ' . $attrNameId;
$data = $readConnection->fetchPairs($query);
foreach($data as $value => $value_id){
    foreach(explode(' ', $value) as $word){
        $result = checkWord($word);
        if($result['has_error']){
            $count++;
            printf("error in %d:  Word %s \n\r", $value_id, $word);
        }
    }
}
echo "end. Found error(s) " . $count . PHP_EOL;


function checkWord($word){
    $ecoding = mb_detect_encoding($word);
    if($ecoding != 'UTF-8'){
        echo '---------' . $ecoding . PHP_EOL;
    }
    $pos = 0;
    $enCount = 0;
    $rusCount = 0;
    $status = array(
        'enCount' => 0,
        'rusCount' => 0,
        'total' => mb_strlen($word),
        'has_error' => false,
        'error_pos' => null,
        'lt' => array(),
        'word' => $word
    );

    foreach(preg_split('//u',$word,-1,PREG_SPLIT_NO_EMPTY) as $letter){
        $letterCode = _uniord($letter);
        if($letterCode >= 1040 && $letterCode <= 1103){
            $status['rusCount'] += 1;
        }
        if($letterCode >= 65 && $letterCode <= 90 || $letterCode >= 97 && $letterCode <= 122){
            $status['enCount'] += 1;
        }
        $status['lt'][$pos]= array(
            'letter' => $letter,
            'code' => $letterCode,
            'position' => $pos,
        );
        if(!$status['has_error'] && $status['enCount'] > 0 && $status['rusCount'] > 0){
            //if($rusCount  == $status['rusCount'] + 1  ||  $enCount == $status['enCount'] + 1)
            $status['has_error'] = true;
            $status['error_pos'] .= $pos . ',';
            $rusCount = $status['rusCount'];
            $enCount = $status['enCount'];
        }
        $pos++;
    }
    /*if($status['enCount'] - $status['rusCount'] != 0){
        $status['has_error'] = true;
    }*/
    return $status;
}

function fixCharacter(){

}


function _uniord($c) {
    if (ord($c{0}) >=0 && ord($c{0}) <= 127)
        return ord($c{0});
    if (ord($c{0}) >= 192 && ord($c{0}) <= 223)
        return (ord($c{0})-192)*64 + (ord($c{1})-128);
    if (ord($c{0}) >= 224 && ord($c{0}) <= 239)
        return (ord($c{0})-224)*4096 + (ord($c{1})-128)*64 + (ord($c{2})-128);
    if (ord($c{0}) >= 240 && ord($c{0}) <= 247)
        return (ord($c{0})-240)*262144 + (ord($c{1})-128)*4096 + (ord($c{2})-128)*64 + (ord($c{3})-128);
    if (ord($c{0}) >= 248 && ord($c{0}) <= 251)
        return (ord($c{0})-248)*16777216 + (ord($c{1})-128)*262144 + (ord($c{2})-128)*4096 + (ord($c{3})-128)*64 + (ord($c{4})-128);
    if (ord($c{0}) >= 252 && ord($c{0}) <= 253)
        return (ord($c{0})-252)*1073741824 + (ord($c{1})-128)*16777216 + (ord($c{2})-128)*262144 + (ord($c{3})-128)*4096 + (ord($c{4})-128)*64 + (ord($c{5})-128);
    if (ord($c{0}) >= 254 && ord($c{0}) <= 255)    //  error
        return FALSE;
    return 0;
}

function ordutf8($string, &$offset) {
    $code = ord(substr($string, $offset,1));
    if ($code >= 128) {        //otherwise 0xxxxxxx
        if ($code < 224) $bytesnumber = 2;                //110xxxxx
        else if ($code < 240) $bytesnumber = 3;        //1110xxxx
        else if ($code < 248) $bytesnumber = 4;    //11110xxx
        $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) - ($bytesnumber > 3 ? 16 : 0);
        for ($i = 2; $i <= $bytesnumber; $i++) {
            $offset ++;
            $code2 = ord(substr($string, $offset, 1)) - 128;        //10xxxxxx
            $codetemp = $codetemp*64 + $code2;
        }
        $code = $codetemp;
    }
    $offset += 1;
    if ($offset >= strlen($string)) $offset = -1;
    return $code;
}