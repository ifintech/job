package job

import (
	"github.com/garyburd/redigo/redis"
	"strconv"
	"strings"
	"time"
	"util"
	"runtime"
)

const Allow_Parallel_Job_Count = 3 //允许单核cpu并行任务数

func CheckCronJob(job map[string]string) bool {
	//检查当前时间是否需要执行定时程序
	if checkTime(job["crontab"]) == false {
		return false
	}

	//当第一轮调度 如果机器平均负载较高或机器并行任务数大于5则不执行
	t := time.Now()
	if t.Second() < 30 && (!util.Able_To_Run || util.Run_Job_Count > int32(Allow_Parallel_Job_Count*runtime.NumCPU())) {
		return false
	}

	//加锁
	if lock("JOB-TIMED-"+job["id"]+"_"+t.Format("2006-01-02 15:04")) == false {
		return false
	}

	return true
}

func CheckOnceJob(job map[string]string) bool {
	//如果机器平均负载较高或并行任务数大于5则不执行 除非任务需强制执行
	if (!util.Able_To_Run || util.Run_Job_Count > int32(Allow_Parallel_Job_Count*runtime.NumCPU())) && job["force"] != ONCE_FORCE_RUN {
		return false
	}

	//加锁
	if lock("JOB-ONCE"+"-"+job["id"]) == false {
		return false
	}

	return true
}

//加锁方法
func lock(key string) bool {
	redis_con, err := util.ConnectRedis()
	defer redis_con.Close()
	if err != nil {
		return false
	}

	ret, _ := redis.Int(redis_con.Do("setnx", "JOB_LOCK_"+key, 1))
	if ret == 1 {
		redis_con.Do("expire", "JOB_LOCK_"+key, 86400)
		return true
	} else {
		return false
	}
}

//依据crontab的时间检查是否需要执行
func checkTime(crontab_time string) bool {
	t := time.Now()
	minute := t.Minute()
	hour := t.Hour()
	day := t.Day()
	month := int(t.Month())
	week := int(t.Weekday())

	attr := strings.Split(crontab_time, " ")
	if inTime(attr[0], minute) && inTime(attr[1], hour) && inTime(attr[2], day) && inTime(attr[3], month) && inTime(attr[4], week) {
		return true
	} else {
		return false
	}
}

func inTime(time string, now_time int) bool {
	array := strings.Split(time, ",")
	if len(array) < 1 {
		return false
	}
	if array[0] == "*" {
		return true
	}
	for _, v := range array {
		i, _ := strconv.Atoi(v)
		if now_time == i {
			return true
		}
	}
	return false
}
