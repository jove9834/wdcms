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

use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\facade\App;

class Generate extends Command
{

    public function configure()
    {
        $this->setName('g')
            ->addOption('module', 'm', Option::VALUE_OPTIONAL,
                '所属模块名称', 'index')
            ->addOption('table', 't', Option::VALUE_REQUIRED,
                '表名')
            ->addOption('conn', 'c', Option::VALUE_OPTIONAL,
                '数据库连接', 'default')
            ->addOption('template', 'T', Option::VALUE_OPTIONAL,
                '生成代码模板，默认EIP', 'EIP')
            ->addOption('code', 'C', Option::VALUE_OPTIONAL,
                '生成代码部分， model, controller, view, config')
            ->setDescription('代码生成工具');
    }

    public function execute(Input $input, Output $output)
    {
        $module = $input->getOption('module');
        $table = $input->getOption('table');
        $conn = $input->getOption('conn');
        $template = $input->getOption('template');
        $code = $input->getOption('code');

        $output->writeln(sprintf('module: ', $module));
        $output->writeln(sprintf('table: ', $table));
        $output->writeln(sprintf('connection: ', $conn));
        $output->writeln(sprintf('template: ', $template));
        $output->writeln(sprintf('code: ', $code));
        // passthru($command);
    }

}
