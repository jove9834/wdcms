CREATE DATABASE `gencode` /*!40100 DEFAULT CHARACTER SET utf8 */;

CREATE TABLE `gen_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `table` varchar(30) NOT NULL,
  `connection` varchar(20) NOT NULL,
  `module` varchar(20) NOT NULL,
  `controller` varchar(60) NOT NULL,
  `func` varchar(60) NOT NULL DEFAULT '' COMMENT '功能JSON：[multi, add, edit, delete, exportExcel]',
  `create_at` datetime(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='功能页面管理';

CREATE TABLE `gen_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL COMMENT '字段名称',
  `title` varchar(60) DEFAULT NULL COMMENT '字段标题',
  `default_value` varchar(60) DEFAULT NULL,
  `validator` varchar(255) DEFAULT NULL COMMENT '验证规则配置JSON格式',
  `alias` varchar(20) DEFAULT NULL COMMENT '显示别名，对应model中的别名属性',
  `update_time` datetime(6) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='字段配置';


CREATE TABLE `gen_edit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `component` varchar(20) NOT NULL,
  `attribute` varchar(255) NOT NULL DEFAULT '' COMMENT '组件属性， JSON格式',
  `add_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 不显示，1 可写， 2只读',
  `edit_status` tinyint(4) NOT NULL DEFAULT '1',
  `sort_num` tinyint(4) NOT NULL DEFAULT '1' COMMENT '排序，按从大到小排',
  `update_time` datetime(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='编辑页面配置';

CREATE TABLE `gen_search_condition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `field_name` varchar(20) NOT NULL,
  `title` varchar(45) NOT NULL,
  `component` varchar(20) NOT NULL,
  `attribute` varchar(255) NOT NULL DEFAULT '',
  `sort_num` tinyint(4) NOT NULL DEFAULT '10',
  `update_time` datetime(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='查询条件';

CREATE TABLE `gen_search_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `field_name` varchar(20) NOT NULL,
  `title` varchar(40) NOT NULL,
  `alias` varchar(20) NOT NULL DEFAULT '',
  `width` smallint(6) NOT NULL DEFAULT '10',
  `hidden` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否隐藏，0 显示，1隐藏',
  `head_align` varchar(10) NOT NULL DEFAULT 'left' COMMENT '对齐，left, center, right',
  `body_align` varchar(10) NOT NULL DEFAULT 'left',
  `sort_num` tinyint(4) NOT NULL DEFAULT '10',
  `update_time` datetime(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='查询列表';
