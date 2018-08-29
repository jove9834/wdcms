<?php
/**
 * 组件配置
 *
 * 组件公共属性： 宽、高
 */

return [
    'text' => [
        'name' => '文本框',
        'attributes' => [

        ]
    ],
    'textarea' => [
        'name' => '多行文本框',
        'attributes' => [
            'rows' => '行数',
        ]
    ],
    'password' => [
        'name' => '密码框',
        'attributes' => [
            'width' => '宽',
        ]
    ],
    'select' => [
        'name' => '下拉框',
        'attributes' => [
            'options' => '选项',
            'width' => '宽',
        ]
    ],
    'selectDict' => [
        'name' => '下拉框(字典)',
        'type' => [
            'options' => [

            ],
            'dict' => ''
        ],
        'attributes' => [
            'dict' => '字典',
            'width' => '宽',
        ]
    ],
    'radio' => [
        'name' => '单选框',
        'attributes' => [
            'dict' => '字典'
        ]
    ],
    'checkbox' => [
        'name' => '复选框',
        'attributes' => [
            'dict' => '字典'
        ]
    ],
    'date' => [
        'name' => '日期选择',
        'attributes' => [
            'format' => '格式'
        ]
    ],
    'datetime' => '日期时间选择',
    'daterange' => '日期范围选择',
    'fileupload' => '文件上传',
    'imageupload' => '图片上传',
    'htmlEditor' => '富文本编辑框',
];