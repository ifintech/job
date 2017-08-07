package util

import (
	"log"
	"os"
)

var logger *log.Logger

func InitLog() {
	logger = log.New(os.Stdout, "", log.LstdFlags)
	logger.SetPrefix(Local_IP + " ")
}

func InfoLog(log string) {
	logger.Printf("info:%s\n", log)
}

func DebugLog(log string) {
	logger.Printf("debug:%s\n", log)
}

func WarningLog(explain string, err error) {
	if err != nil {
		logger.Printf("warning:%s | %s\n", explain, err.Error())
	} else {
		logger.Printf("warning:%s\n", explain)
	}
}

func ErrorLog(explain string, err error) {
	if err != nil {
		logger.Printf("error:%s | %s\n", explain, err.Error())
	} else {
		logger.Printf("error:%s\n", explain)
	}
}

func PanicLog(explain string, err error) {
	if err != nil {
		logger.Panicf("error:%s | %s\n", explain, err.Error())
	} else {
		logger.Panicf("error:%s\n", explain)
	}
}
