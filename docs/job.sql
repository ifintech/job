CREATE TABLE `job_timed` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `app_name` varchar(128) NOT NULL COMMENT '项目名称',
  `command` varchar(1024) NOT NULL COMMENT '执行命令',
  `crontab` varchar(255) NOT NULL COMMENT '执行频率',
  `retry` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0不重试 1允许重试',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1激活2停用',
  `note`  varchar(1024) not null comment '说明',
  `proposer` varchar(100) not null default '' comment '申请人',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_app_status` (`app_name`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `job_once` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `app_name` varchar(128) NOT NULL COMMENT '项目名称',
  `command` varchar(1024) NOT NULL COMMENT '执行命令',
  `retry` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0不重试 1允许重试',
  `force`   tinyint NOT NULL DEFAULT 0 comment '是否即时调度 1即时',
  `proposer` varchar(100) not null default '' comment '申请人',
  `note`  varchar(1024) not null comment '说明',
  `status` tinyint unsigned not null default 0 comment '0等待调度 1调度成功',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_app_status` (`app_name`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

create table `job_log` (
  `id` int unsigned not null AUTO_INCREMENT,
  `app_name` VARCHAR(128) not null comment "项目名称",
  `job_id` int not null,
  `machine_ip` char(16) not null comment "执行主机IP",
  `type` tinyint unsigned not null comment '任务类型 0定时任务 1一次性任务',
  `exec_start_time` timestamp not null default '0000-00-00 00:00:00',
  `exec_end_time` timestamp not null default '0000-00-00 00:00:00',
  `exec_info` text comment "执行状况",
  `exec_export` text comment "结果输出",
  `status` tinyint(1) unsigned not null default 0 comment "0执行中 1成功 2失败",
  `ctime` timestamp not null default current_timestamp ,
  `mtime` timestamp not null DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY key (`id`),
  key `idx_iobid` (`job_id`),
  key `idx_app_status` (`app_name`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;