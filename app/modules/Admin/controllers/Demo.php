<?php

use Admin\Model\Data\Demo as AdminDataDemo;

/**
 * @name admin控制器示范
 */
class Controller_Demo extends \Modules\Admin\Controllers\Controller {

    /**
     * @name 示范页面
     */
    public function indexAction() {
    }

    /**
     * @name 访问记录数据源
     */
    public function listAction() {
        $this->response = (new AdminDataDemo())->queryDataTable();
    }

}
