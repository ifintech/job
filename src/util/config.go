package util

type MysqlConfig struct {
	Host     string
	Username string
	Password string
	Dbname   string
	Charset  string
}

type RedisConfig struct {
	Address string
	Auth    string
	Db      int
}

type JobConfig struct {
	Mysql MysqlConfig
	Redis RedisConfig
	Env   string
}

var Config JobConfig = JobConfig{MysqlConfig{"172.16.22.82:3306", "root", "hrbbwx.com", "job", "utf8"}, RedisConfig{"172.16.22.82:6379", "", 0}, "dev"}
