package main

import (
	"io/ioutil"
	"job"
	"os"
	"os/signal"
	"runtime"
	"strconv"
	"syscall"
	"time"
	"util"
)

func main() {

	//初始化
	runtime.GOMAXPROCS(runtime.NumCPU()) //使用上多核
	util.DbConnect() //获取数据库连接池
	util.GetLocalIP()
	util.LogInit()
	ioutil.WriteFile("/var/run/JOB_AGENT_PID", []byte(strconv.Itoa(os.Getpid())), 0666)
	sigs := make(chan os.Signal, 1)
	signal.Notify(sigs, syscall.SIGINT, syscall.SIGTERM) //注册通道用于接收终止进程运行的系统信号
	util.InfoLog("job ready")

	//等待整分时才开始工作
	for {
		select {
		case <-sigs:
			util.InfoLog("job end")
			ioutil.WriteFile("/var/run/JOB_AGENT_PID", []byte(""), 0666)
			os.Exit(1)
		default:
			if time.Now().Second() == 0 {
				break
			}
			time.Sleep(time.Second)
		}
	}
	util.InfoLog("job start work")
	//工作
	tick_10 := time.NewTicker(time.Second * 10)
	tick_30 := time.NewTicker(time.Second * 30)
Work:
	for {
		select {
		case <-tick_10.C:
			go doOnceJob()
		case <-tick_30.C:
			go doCronJob()
		case <-sigs:
			break Work
		}
	}

	//回收协程 准备退出
	util.InfoLog("job ready to exit")
	util.WG.Wait()
	util.InfoLog("job end")
	ioutil.WriteFile("/var/run/JOB_AGENT_PID", []byte(""), 0666)
}

func doCronJob() {
	jobs := job.GetCronJobList()
	for _, job_info := range jobs {
		if job.CheckCronJob(job_info) {
			go job.Run(job_info, job.JOB_TYPE_CRON)
		}
	}
}

func doOnceJob() {
	jobs := job.GetOnceJobList()
	for _, job_info := range jobs {
		if job.CheckOnceJob(job_info) {
			go job.Run(job_info, job.JOB_TYPE_ONCE)
		}
	}
}

