<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Slince <taosikai@yeah.net>
// +----------------------------------------------------------------------
namespace app\gencode;

use app\gencode\common\Build;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Generate extends Command
{

    public function configure()
    {
        $this->setName('g')
            ->addOption('module', 'm', Option::VALUE_OPTIONAL,
                '所属模块名称', 'index')
            ->addOption('conn', 'c', Option::VALUE_OPTIONAL,
                '数据库连接', '')
            ->addOption('template', 't', Option::VALUE_OPTIONAL,
                '生成代码模板，默认EIP', 'EIP')
            ->addOption('code', 'C', Option::VALUE_OPTIONAL,
                '生成代码部分， model, controller, view, config')
            ->addArgument('table', Argument::REQUIRED,'表名')
            ->setDescription('代码生成工具');
    }

    public function execute(Input $input, Output $output)
    {
        $module = $input->getOption('module');
        $conn = $input->getOption('conn');
        $template = $input->getOption('template');
        $code = $input->getOption('code');
        $table = trim($input->getArgument('table'));

        try {
            if ($code === 'config') {
                // 生成配置文件
                $fileName = Build::makeConfig($module, $conn, $table);
            } else {
                // 生成其他功能代码
                $fileName = '';
            }

            $output->writeln(sprintf('module: %s', $module));
            $output->writeln(sprintf('table: %s', $table));
            $output->writeln(sprintf('connection: %s', $conn));
            $output->writeln(sprintf('template: %s', $template));
            $output->writeln(sprintf('code: %s', $code));
            $output->writeln(sprintf('generate file: %s', $fileName));
        } catch (\Exception $e) {
            $output->renderException($e);
        }
    }

}
