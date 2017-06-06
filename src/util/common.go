package util

import (
	"database/sql"
	"github.com/garyburd/redigo/redis"
	"strconv"
	"sync"
)

var Db *sql.DB
var App_Name string
var WG sync.WaitGroup
var Run_Job_Count int32

//连接redis
func ConnectRedis() (redis.Conn, error) {
	conn, err := redis.Dial("tcp", Config.Redis.Address)
	if err != nil {
		ErrorLog("redis连接失败", err)
		return nil, err
	}

	if Config.Redis.Auth != "" {
		_, err = conn.Do("AUTH", Config.Redis.Auth)
		if err != nil {
			ErrorLog("redis认证失败", err)
			return nil, err
		}
	}
	if Config.Redis.Db != 0 {
		_, err = conn.Do("SELECT", Config.Redis.Db)
		if err != nil {
			ErrorLog("redis连接DB失败", err)
			return nil, err
		}
	}

	return conn, err
}

//查询数据库
func Query(query_sql string, args ...interface{}) (map[string]map[string]string, error) {
	ret := make(map[string]map[string]string)
	rows, err := Db.Query(query_sql, args...)
	if err != nil {
		ErrorLog("查询失败:"+query_sql, err)
		return nil, err
	}

	//构造scanArgs、values两个数组，scanArgs的每个值指向values相应值的地址
	columns, _ := rows.Columns()
	scanArgs := make([]interface{}, len(columns))
	values := make([][]byte, len(columns))
	for i := range values {
		scanArgs[i] = &values[i]
	}

	//最后得到的map
	i := 0
	for rows.Next() {
		err = rows.Scan(scanArgs...)
		record := make(map[string]string) //每行数据
		for k, v := range values {        //每行数据是放在values里面，现在把它挪到row里
			record[columns[k]] = string(v)
		}
		ret[strconv.Itoa(i)] = record //装入结果集中
		i++
	}

	return ret, nil
}

//插入数据到数据库
func Insert(insert_sql string, args ...interface{}) (int64, error) {
	res, err := Db.Exec(insert_sql, args...)
	if err != nil {
		ErrorLog("添加失败:"+insert_sql, err)
		return 0, err
	}
	id, _ := res.LastInsertId()
	return id, nil
}

//更新数据库数据
func Update(update_sql string, args ...interface{}) (bool, error) {
	_, err := Db.Exec(update_sql, args...)
	if err != nil {
		ErrorLog("更新失败:"+update_sql, err)
		return false, err
	}
	return true, nil
}

//连接数据库
func DbConnect() {
	dsn := Config.Mysql.Username + ":" + Config.Mysql.Password + "@tcp(" + Config.Mysql.Host + ")/" + Config.Mysql.Dbname + "?charset=" + Config.Mysql.Charset
	var err error
	Db, err = sql.Open("mysql", dsn)
	if err != nil {
		PanicLog("数据库连接失败", err)
	}
}
