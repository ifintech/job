<?php
namespace Jobs\Daemon\Demo;

/**
 * Class Worker
 *
 * @description 守护子进程示范
 *
 *              备注:
 *              1. 守护子进程必须继承\S\Thread\Worker;
 *              2. 使用守护子进程典型场景: 生产-消费者模型中的消费者;
 *              3. 禁止在worker进程中进行类似 while(true) 或 for(;;) 等类死循环操作, 否则进程可能无法被信号量kill;
 */
class Worker extends \S\Thread\Worker {

    public $whileSleep = 0;  // 设置为0时默认外层睡眠10毫秒

    /**
     * 任务处理函数
     *
     * @access public
     * @return void
     */
    public function process() {
        // 典型业务逻辑: 任务队列消费者
        $task = $this->consumeFromQueue();
        /**
         * 注意:
         *
         * **************************************************************************************
         * 禁止在worker进程中进行类似 while(true) 或 for(;;) 等类死循环操作, 否则进程可能无法被信号量kill
         * **************************************************************************************
         */
        if (!$task) {
            // 无任务时主动睡眠一段时间, 避免不必要的系统资源消耗
            sleep(5);
        }

        $this->doSomething($task);
    }

    /**
     * 消费者示范逻辑
     *
     * @return array 任务
     */
    private function consumeFromQueue() {
        return array();
    }

    /**
     * 示范逻辑
     *
     * @param array $task 任务
     *
     * @return bool
     */
    private function doSomething(array $task) {
        return true;
    }

}