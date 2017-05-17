<?php
//设置全局的编码
ini_set('default_charset', \Core\Env::$charset);
//设置全局时区
ini_set('date.timezone', 'Asia/Shanghai');

/**
 * 域名及url定义
 *
 * *******************************
 * 注意:
 * 此文件中仅包括应用名和域名相关宏定义
 * 任何人不能在此文件中随意定义宏
 * 有添加需求时必须联系项目负责人
 * *******************************
 */
if(\Core\Env::isProductEnv()){
    // 生产环境APP相关定义
    define('APP_NAME',  'job');  //应用名称
    define('APP_DOMAIN',    'job.com');  //公网域名, 上线前需要确认更改为正式公网域名
    define('APP_INTER_DOMAIN', 'job.com');  //内网域名, 上线前需要确认更改为正式内网域名
    define('APP_HOST_URL',  'https://'.APP_DOMAIN);  //公网服务根url
    define('APP_INTER_HOST_URL',  'http://'.APP_INTER_DOMAIN);  //内网服务根url
    define('APP_STATIC_PATH',   '/static');  //静态资源路径
    define('APP_ADMIN_PATH',    '/admin'); //管理模块资源路径
}else{
    // 开发环境APP相关定义
    define('APP_NAME',  'job');
    define('APP_DOMAIN',    'job.com');
    define('APP_INTER_DOMAIN', 'job.com');
    define('APP_HOST_URL',  'http://'.APP_DOMAIN);
    define('APP_INTER_HOST_URL',  'http://'.APP_INTER_DOMAIN);
    define('APP_STATIC_PATH',   '/static');
    define('APP_ADMIN_PATH',    '/admin');
}