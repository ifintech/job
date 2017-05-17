<?php
/**
 * 通用系统异常定义
 *
 * 错误码2000000定义说明:
 * 参考http响应码200定义, 使用2000000代表请求成功被接收、理解并处理
 * @document https://tools.ietf.org/html/rfc2616#page-58
 *
 * rfc规范中对于5**格式状态码描述如下:
 * The request has succeeded. The information returned with the response
 * is dependent on the method used in the request.
 * _____________________________________________________________________
 *
 * 5开头错误码定义说明:
 * 参考http响应码5**定义, 当遇到5开头的错误码时认为本次请求出现异常
 * @document https://tools.ietf.org/html/rfc2616#page-70
 *
 * rfc规范中对于5**格式状态码描述如下:
 * Response status codes beginning with the digit "5" indicate cases in
 * which the server is aware that it has erred or is incapable of
 * performing the request.
 */
return array(
    'succ' => array('retcode'  => 2000000, 'msg'   => '成功'),
    'sys_error' => array('retcode' => 5000000, 'msg' => '系统繁忙', 'user_msg' => '系统繁忙'),
);