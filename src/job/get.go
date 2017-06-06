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

const ONCE_FORCE_RUN = "1" //一次性任务强制执行

const TMP_FILE_TIMED_PRE = "/tmp/jobs_timed_"

var ret_cache string

//获取定时任务
func GetCronJobList(app_name string) map[string]map[string]string {
	query_sql := "select * from job_timed where app_name = ? and status = 1" //可执行的任务
	ret, err := util.Query(query_sql, app_name)
	if err == nil {
		ret_json, _ := json.Marshal(ret)
		if string(ret_json) != ret_cache { //文件缓存
			ret_cache = string(ret_json)
			ioutil.WriteFile(TMP_FILE_TIMED_PRE+app_name, ret_json, 0644)
		}
	} else {
		ret_byte, _ := ioutil.ReadFile(TMP_FILE_TIMED_PRE + app_name)
		json.Unmarshal(ret_byte, &ret)
	}
	return ret
}

//获取一次性任务
func GetOnceJobList(app_name string) map[string]map[string]string {
	query_sql := "select * from job_once where app_name = ? and status = ?" //未处理的任务
	ret, _ := util.Query(query_sql, app_name, ONCE_STATUS_UNTREATED)
	return ret
}
