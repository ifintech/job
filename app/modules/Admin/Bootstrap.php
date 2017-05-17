<?php
class Bootstrap extends \Base\Bootstrap {

    public function _initDefine(){
        define('APPLICATION_BASE_TPL_PATH', PHPLIB . "/Modules/Admin/Views/");
        define('ADMIN_SYS_NAME', "job管理系统"); // 后台管理名称
    }

    public function _initPlugin(\Yaf\Dispatcher $dispatcher){
        $dispatcher->registerPlugin(new Plugin_Admin());
    }

    /**
     * 在此处注册非YAF的autoload
     * 注册YAF的localnamespace和map
     */
    public function _initBaseLoder() {
        parent::_initBaseLoder();
        \Core\Loader::register_autoloader(array('modules\\Admin'));
    }
}