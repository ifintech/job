<?php
namespace Dao;
/**
 * Class Db
 *
 * @package Dao
 * @description rds数据库服务基类
 */
class Db extends \Base\Dao\Db {

	const DB_NAME       = 'job';  //库名, 与项目名称一致
	const TABLE_PREFIX  = 'job_';  //表前缀, 与项目名称一致

    /**
     * 选择库实例
     *
     * @param string $name 库名, 默认与项目名称一致
     *
     * @return \S\Db\Mysql MySQL实例
     */
    public static function db($name=self::DB_NAME) {
        return self::getInstance($name);
    }

    /**
     * 选择表
     * 为避免可能与其他无关项目的表混淆, 所有表名应带有项目名称的表前缀, 获取表名必须使用此方法
     *
     * @param string $table 表名
     *
     * @return string 带有项目名称作为前缀的表名, 格式: {APP_NAME}_table
     */
    public static function table($table) {
		return self::TABLE_PREFIX.$table;
    }

}
