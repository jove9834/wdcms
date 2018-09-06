<?php
//分页配置
defined('PER_PAGE') || define('PER_PAGE', 15);
return [
    'type'      => 'app\common\JsonPaginator',
    'var_page'  => 'page',
    'list_rows' => PER_PAGE,
];
