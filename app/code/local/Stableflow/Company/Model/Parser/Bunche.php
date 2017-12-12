<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 11/3/17
 * Time: 2:38 PM
 */
class Stableflow_Company_Model_Parser_Bunche implements IteratorAggregate
{
    private $array = [];
    const TYPE_INDEXED = 1;
    const TYPE_ASSOCIATIVE = 2;

    public function __construct( array $data, $type = self::TYPE_INDEXED ) {
        reset($data);
        while( list($k, $v) = each($data) ) {
            $type == self::TYPE_INDEXED ?
                $this->array[] = $v :
                $this->array[$k] = $v;
        }
    }

    public function getIterator() {
        return new ArrayIterator($this->array);
    }
}