package job

import (
	"encoding/json"
	_ "github.com/go-sql-driver/mysql"
	"io/ioutil"
	"util"
)

const ONCE_STATUS_UNTREATED = 0
const ONCE_STATUS_PROCESSING = 1
const ONCE_STATUS_SUCC = 2
const ONCE_STATUS_FAIL = 3

const JOB_TYPE_CRON = 0 //定时任务
const JOB_TYPE_ONCE = 1 //一次性任务

const LOG_STATUS_SUCC = 1
const LOG_STATUS_FAIL = 2

const TMP_FILE_TIMED = "/tmp/jobs_timed"

var ret_cache string

//获取定时任务
func GetCronJobList() map[string]map[string]string {
	query_sql := "select * from job_timed where status = ?"
	ret, err := util.Query(query_sql, 1) //1代表任务可以执行
	if err == nil {
		ret_json, _ := json.Marshal(ret)
		if string(ret_json) != ret_cache { //文件缓存
			ret_cache = string(ret_json)
			ioutil.WriteFile(TMP_FILE_TIMED, ret_json, 0644)
		}
	} else {
		ret_byte, _ := ioutil.ReadFile(TMP_FILE_TIMED)
		json.Unmarshal(ret_byte, &ret)
	}
	return ret
}

//获取一次性任务
func GetOnceJobList() map[string]map[string]string {
	query_sql := "select * from job_once where status = ?" //未处理的任务
	ret, _ := util.Query(query_sql, ONCE_STATUS_UNTREATED)
	return ret
}
