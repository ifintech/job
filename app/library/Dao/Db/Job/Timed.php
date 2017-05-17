<?php
namespace Dao\Db\Job;

class Timed extends \Dao\Db{

    const STATUS_ABLE = 1;
    const STATUS_DISABLE = 2;

    private $table;
    public function __construct(){
        $this->table = 'job_timed';
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
