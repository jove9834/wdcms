<?php
/**
 * 输出文件名规则：
 *
 * {UF_name} 首字母大写
 * {name} 为表名驼峰命名方式
 *
 * output: 为输出目录，相对于设置的输出根目录，为空，则以模板类型名为目录名，如:controller, model等
 */

return [
    'default' => [
        'controller' => [
            'template' => '模板文件',
            'output' => '输出目录',
            'fileName' => '输出文件名规则'
        ],
        'model' => '',
        'route' => '',
        'search' => '',
        'edit' => '',
        'api' => '',
    ]
];