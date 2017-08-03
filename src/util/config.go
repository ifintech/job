package util

import (
	"io/ioutil"
	"encoding/json"
	"os"
	"fmt"
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

var Config JobConfig

func InitConfig(config_path string) (){
	config_byte, err := ioutil.ReadFile(config_path)
	if(err != nil){
		fmt.Println("配置文件获取失败: "+config_path)
		os.Exit(1)
	}
	json.Unmarshal(config_byte, &Config)
}