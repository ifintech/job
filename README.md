## 简介

**job-agent**是一个简易的分布式任务调度方案，设计目标是学习简单，使用方便，可用性高，摆脱对**crontab**等单机任务调度方案的依赖，支持一次性任务，定时任务，docker任务的运行。

## 功能

- 统一的任务管理：支持通过Web页面对任务进行CRUD操作，操作简单
- 任务运行记录：可查看任务运行的各种记录及运行结果
- 一致性：任务调度策略通过DB锁保证分布式调度的一致性, 一次任务调度只会触发一次执行
- 高可用：执行端分布式部署，当某台机器失效时，任务会被调度到其他机器上执行
- 高扩展：方便对任务集群进行扩容缩容，只需在集群上添加新的节点

## 快速入门

1. 部署任务节点

   ```shell
   wget -O job-agent https://github.com/ifintech/job/releases/download/v1.0.1/job-agent-amd64-1.0.1
   chmod +x job-agent
   # 如果需要执行docker任务并且依赖于私有仓库镜像 则需要先在机器上登录
   docker login [REPOSITORY_HOST] -u [username] -p [password]
   ```

2. 添加配置 **/etc/job-agent.json**

   *配置模板示例*

   ```json
   {
     "mysql": {
       "host": "127.0.0.1:3306",
       "username": "root",
       "password": "Root1.pwd",
       "dbname": "service",
       "charset": "utf8"
     },
     "redis": {
       "address": "127.0.0.1:6379",
       "auth": "",
       "db": 0
     }
   }
   ```

3. 运行

   ```shell
   nohup ./job-agent -f /etc/job-agent.json >> /var/log/job-agent.log 2>&1 &
   ```

4. 部署完多个任务节点之后，部署[服务端](https://github.com/ifintech/service)

5. 在服务端后台添加任务，等待一段时间后即可看到任务的运行结果

   ![WX20170807-113122@2x](https://ws3.sinaimg.cn/large/006tNc79ly1fir7yqblobj31kw0njq84.jpg)