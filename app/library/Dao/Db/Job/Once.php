<?php
namespace Dao\Db\Job;

class Once extends \Dao\Db{

    const STATUS_NOT_HANDLE = 0; //未调度
    const STATUS_HANDLING = 1; //调度成功

    const RETRY_NO  = 0; //不重试
    const RETRY_YES = 1; //重试

    const FORCE_NO  = 0; //正常调度
    const FORCE_YES = 1; //强制调度

    private $table;
    public function __construct(){
        $this->table = 'job_once';
    }

    public function add($data){
        return self::db()->insert($this->table, $data, true);
    }

    public function up($id, array $info){
        return self::db()->update($this->table, $info, array('id' => $id));
    }

    public function get($id){
        return self::db()->queryone($this->table, array('id' => $id));
    }
}
