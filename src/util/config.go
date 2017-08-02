package util

import (
	"io/ioutil"
	"encoding/json"
)

type JobConfig struct {
	Env   string
	Mysql struct {
		      Host     string
		      Username string
		      Password string
		      Dbname   string
		      Charset  string
	      }
	Redis struct {
		      Address string
		      Auth    string
		      Db      int
	      }
}

const CONFIG_FILE = "/etc/job_agent.json"

var Config JobConfig

func InitConfig() (){
	config_byte, err := ioutil.ReadFile(CONFIG_FILE)
	if(err != nil){
		PanicLog("配置文件获取失败: "+CONFIG_FILE, err)
	}
	json.Unmarshal(config_byte, &Config)
}