<?php

return [
    'connection' => 'default',
    'table' => 't_dict',
    'title' => '字典管理',
    'batch' => true, // 批量操作
    'function' => 'add,edit,delete,detail,export',
    'fields' => [ // 字段默认配置
        'id' => [
            'title' => 'ID',
            'type' => 'int',
            'max_length' => '10',
            'nullable' => false,
            'default' => NULL,
        ],
        'name' => [
            'title' => 'ID',
            'type' => 'int',
            'max_length' => '10',
            'nullable' => false,
            'default' => NULL,
            'validate' => [
                'rule' => 'require|max:25',
                'message' => [
                    'require' => '名称必须',
                    'max' => '名称最多不能超过25个字符'
                ],
            ],
            'component' => 'text',
            'attribute' => [
                'placeholder' => '',
                'width' => '100%'
            ],
            'add_status' => 'readonly|writable|hide',
            'edit_status' => 'readonly|writable|hide',
        ]
    ],
    'form' => [
        'fields' => [], // 表单字段特殊配置
        'default' => ['id', 'name']
    ],
    'search' => [
        'fields' => [], // 查询条件字段特殊配置
        'default' => ['name'],
        'more' => [] // 更多查询条件配置
    ],
    'list' => [
        'id' => [
            'hide' => false,
        ],
        'name' => [
            'title' => '名称',
            'width' => 10,
            'hide' => false,
            'head_align' => 'left|center|right',
            'body_align' => 'left|center|right',
            'alias' => 'name_text'
        ],
        'action' => [
            'title' => '操作',
            'width' => 10,
            'hide' => false,
            'head_align' => 'left|center|right',
            'body_align' => 'left|center|right',
        ],
    ],
];