<?php
/**
 * 组件配置
 */

return [
    'text' => [
        'name' => '文本框',
        'attributes' => [
            'width' => '宽'
        ]
    ],
    'textarea' => [
        'name' => '多行文本框',
        'attributes' => [
            'width' => '宽',
            'height' => '高',
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
        'attributes' => [
            'dict' => '字典',
            'width' => '宽',
        ]
    ],
    'radio' => '单选框',
    'checkbox' => '复选框',
    'date' => '日期选择',
    'datetime' => '日期时间选择',
    'daterange' => '日期范围选择',
    'fileupload' => '文件上传',
    'imageupload' => '图片上传',
    'htmlEditor' => '富文本编辑框',
];