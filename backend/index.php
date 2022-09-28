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
	// 构建路由
	if (!isset($_GET['route'])) {
		$route = 'index';
	}
	if ($route == 'index') {
		// 首页
	} else if ($route == 'toupiao') {
		// 投票
	} else {
		echo 404;
	}
