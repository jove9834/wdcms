<?php
namespace app\gencode\model;

use think\Model;

class User extends Model
{
    // 设置返回数据集的对象名
    protected $resultSetType = '\app\common\Collection';
}