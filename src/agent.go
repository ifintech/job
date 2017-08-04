package main

import (
	"job"
	"os"
	"os/signal"
	"runtime"
	"syscall"
	"time"
	"util"
	"flag"
)

func main() {
	runtime.GOMAXPROCS(runtime.NumCPU()) //使用上多核

	//解析外部输入参数
	config_path := flag.String("f", "/etc/job-agent.json", "config file path")
	flag.Parse()
	util.InitConfig(*config_path)

	//初始化
	util.DbConnect() //数据库连接池
	util.GetLocalIP()
	util.InitLog()
	util.InfoLog("job ready")

	//等待整分时才开始工作
	for {
		if time.Now().Second() == 0 {
			break
		}
		time.Sleep(time.Second)
	}
	util.InfoLog("job start work")

	//工作
	sigs := make(chan os.Signal, 1)
	signal.Notify(sigs, syscall.SIGINT, syscall.SIGTERM) //注册通道用于接收终止进程运行的系统信号
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
