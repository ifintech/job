package util

import (
	"net"
)

var Local_IP string

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
