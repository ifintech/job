<?php
/**
 * 此错误配置仅供参数校验使用
 *
 * 参考http响应码定义中客户端错误码的格式, 参数校验错误属于客户端错误的一种, 因此使用4开头的错误码
 * @document https://tools.ietf.org/html/rfc2616#page-65
 *
 * rfc规范中对于4**客户端错误定义如下:
 * The 4xx class of status code is intended for cases in which the
 * client seems to have erred.
 */
return array(
    'job_id_error'       => array('retcode' => 4001001, 'msg' => '任务编号错误', 'user_msg' => '任务编号错误'),
    'job_command_error'  => array('retcode' => 4001002, 'msg' => '任务命令错误', 'user_msg' => '任务命令错误'),
    'job_note_error'     => array('retcode' => 4001003, 'msg' => '任务说明错误', 'user_msg' => '任务说明错误'),
    'job_params_error'   => array('retcode' => 4001004, 'msg' => '任务参数错误', 'user_msg' => '任务参数错误'),
    'job_info_error'     => array('retcode' => 4001006, 'msg' => '任务执行信息错误', 'user_msg' => '任务执行信息错误'),
    'job_export_error'   => array('retcode' => 4001007, 'msg' => '任务执行输出错误', 'user_msg' => '任务执行输出错误'),
    'job_succ_error'     => array('retcode' => 4001008, 'msg' => '任务执行状态错误', 'user_msg' => '任务执行状态错误'),
    'job_force_error'    => array('retcode' => 4001009, 'msg' => '任务调度方式错误', 'user_msg' => '任务调度方式错误'),
    'job_retry_error'    => array('retcode' => 4001010, 'msg' => '任务错误重试方式错误', 'user_msg' => '任务错误重试方式错误'),
    'run_id_error'       => array('retcode' => 4001011, 'msg' => '任务执行编号错误', 'user_msg' => '任务执行编号错误'),
);