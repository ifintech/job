#!/bin/bash
#############################################################################
# 使用帮助
if [ "-h" = "$1" ] || [ "--help" = "$1" ] || [ -z $1 ]
then
    echo
    echo "介绍: 任务管理 启动|停止|重启|状态"
    echo "用法: sh /data1/htdocs/job.com/shell/service.sh [start|stop|reload|restart|status]"
    exit
fi

if [ "start" = "$1" ]
then
    #启动daemon
    nohup /usr/bin/php /data1/htdocs/job.com/jobs/job.php Jobs_Daemon_Master >> /tmp/nohup.job.Daemon.log 2>&1 &

    echo "succ"
fi

if [ "stop" = "$1" ]
then
    #回收daemon
    kill `cat /var/run/PHP_THREAD_MASTER_PID.job`
    while true; do
        sleep 3
        if [[ `cat /var/run/PHP_THREAD_MASTER_PID.job` = "" ]];
        then
            break
        fi
    done

    echo "succ"
fi

if [ "restart" = "$1" ] || [ "reload" = "$1" ]
then
    #重启daemon
    nohup /usr/bin/php /data1/htdocs/job.com/jobs/job.php Jobs_Daemon_Master >> /tmp/nohup.job.Daemon.log 2>&1 &

    echo "succ"
fi

#返回running or stopped
if [ "status" = "$1" ]
then
    echo "running"
fi
