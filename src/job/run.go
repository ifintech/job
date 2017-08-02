package job

import (
	"github.com/codeskyblue/go-sh"
	"strconv"
	"strings"
	"time"
	"util"
)

//脚本执行方法
func Run(job map[string]string, job_type int) {
	util.InfoLog("job-start " + job["id"] + " " + strconv.Itoa(job_type) + " " + job["command"])
	//记录开始运行
	run_id := jobStart(job, job_type)
	x_rid  := "JOB-" + strconv.FormatInt(run_id, 10)
	//任务计数器加1
	util.WG.Add(1)
	//运行
	session := sh.NewSession()
	session.SetDir("/")
	session.SetEnv("x-rid", x_rid)
	command := strings.Split(job["command"], " ")
	output, err := session.Command(command[0], command[1:]).Output()

	var succ bool
	var exec_export string
	if err != nil {
		succ = false
		util.DebugLog("job-error " + job["id"] + " error:" + err.Error())
		exec_export = string(output) + "\n" + err.Error()
	} else {
		succ = true
		exec_export = string(output)
	}
	//记录运行结果
	jobEnd(run_id, succ, exec_export)
	//任务计数器减一
	util.WG.Done()
}

func jobStart(job map[string]string, job_type int) int64 {
	if job_type == JOB_TYPE_ONCE { //如果为一次性任务 则更新他的执行状态
		update_sql := "update job_once set status = ? where id = ?"
		util.Update(update_sql, ONCE_STATUS_PROCESSING, job["id"])
	}
	//写入任务执行日志
	insert_sql := "insert into job_log (app_name, job_id, machine_ip, type, exec_start_time) values (?, ?, ?, ?, ?)"
	now_time := time.Now().Format("2006-01-02 15:04:05")
	run_id, _ := util.Insert(insert_sql, job["app_name"], job["id"], util.Local_IP, job_type, now_time)

	return run_id
}

func jobEnd(run_id int64, succ bool, export string) bool {
	if len(export) > 65535 {
		export = export[:65530] + "..."
	}
	now_time := time.Now().Format("2006-01-02 15:04:05")
	var log_status int
	if succ {
		log_status = LOG_STATUS_SUCC
	} else {
		log_status = LOG_STATUS_FAIL
	}
	//更新执行日志
	update_sql := "update job_log set status = ?, exec_export = ?, exec_end_time = ? where id = ?"
	util.Update(update_sql, log_status, export, now_time, run_id)
	return true
}
