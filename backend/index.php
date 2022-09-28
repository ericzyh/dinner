<?php
	// 直接输出错误
	ini_set('display_errors', 'on');
	error_reporting(E_ALL);
	// composer 加载
	include (__DIR__."/vendor/autoload.php");
	// 数据库配置加载
	$dbconfig = include(__DIR__."/db.config.php");
	// 获取数据库连接
	use Medoo\Medoo;
	$database = new Medoo([
	    // 必须配置项
	    'database_type' => 'mysql',
	    'database_name' => $dbconfig['name'],
	    'server' => $dbconfig['host'],
	    'username' => $dbconfig['username'],
	    'password' => $dbconfig['password'],
	    'charset' => 'utf8',
	    'port' => $dbconfig['port'],
	]);
	// 构建路由 默认路由index
	if (!isset($_GET['route'])) {
		$route = 'index';
	} else {
		$route = $_GET['route'];
	}

	// 统一返回方法
	function ajaxResponse($code = '200', $data = [], $msg = '') {
		echo json_encode( [
			'code' => $code,
			'data' => $data,
			'msg' => $msg
		]);
	}

	// 根据ip获取用户
	// 找不到用户提示联系系统管理员

	// 首页
	if ($route == 'index') {
		// 查看用户今天投票的情况
		// 查看今天的投票选项
		// 选项没有生成提示请4点以后再来
	} else if ($route == 'toupiao') {
		// 投票
		// 发送投票通知 (xxxxxx已经投票)
	} else {
		echo 404;
	}
