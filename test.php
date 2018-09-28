<?php
/**
 * Created by PhpStorm.
 * User: IGG
 * Date: 2018/9/28
 * Time: 17:32
 */
$procDefKeys = ['a', 'b'];
$pdKeys = NULL;
$a = array_values(array_intersect($procDefKeys, $pdKeys));
var_dump($a);
if ($a) {
    echo 'aaaaaaaaaaaa';
} else {
    echo 'bbbbbbbbbb';
}