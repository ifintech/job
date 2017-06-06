package util

import (
	"github.com/codeskyblue/go-sh"
	"net"
	"strconv"
	"strings"
	"time"
	"runtime"
)

var Local_IP string
var Able_To_Run  bool

//注册机器 更新机器的平均负载
func Register() {
	//获取docker一分钟内的平均负载
	session := sh.NewSession()
	session.SetDir("/")
	output, _ := session.Command("uptime").Output()
	attr := strings.Split(string(output), " ")
	load_average := strings.Trim(attr[len(attr)-2], ",")
	//机器竞选 判断机器是否符合执行任务的条件
	runFor(load_average)
	//注册服务
	key := "JOB_AGENT_" + App_Name
	value := strconv.FormatInt(time.Now().Unix(), 10) + "|" + load_average
	redis_con, err := ConnectRedis()
	if err == nil {
		redis_con.Do("hset", key, Local_IP, value)
		defer redis_con.Close()
	}
}

//机器竞选
func runFor(load_average string){
	local_load_average, err := strconv.ParseFloat(load_average, 64)
	if(err != nil){
		PanicLog("机器平均负载获取失败", err)
	}
	if(local_load_average < float64(runtime.NumCPU()*3)){
		Able_To_Run = true
	}else{
		Able_To_Run = false
	}
}

//获取本地ip
func GetLocalIP() {
	var ip string
	addrs, _ := net.InterfaceAddrs()
	for _, address := range addrs {
		// 检查ip地址判断是否回环地址
		if ipnet, ok := address.(*net.IPNet); ok && !ipnet.IP.IsLoopback() {
			if ipnet.IP.To4() != nil {
				ip = ipnet.IP.String()
			}
		}
	}

	if ip != "" {
		Local_IP = ip
	} else {
		PanicLog("获取ip地址失败", nil)
	}
}
