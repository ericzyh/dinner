<?php
	// 直接输出错误
	ini_set('display_errors', 'on');
	error_reporting(E_ALL);
	// composer 加载
	include (__DIR__."/vendor/autoload.php");
	// 数据库配置加载
	$dbconfig = include(__DIR__."/config/db.config.php");
	// 应用配置加载
	$appconfig = include(__DIR__."/config/app.config.php");
	// 获取数据库连接
	use Medoo\Medoo;
	use util\LogService;
	use util\WechatService;
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

	// 开始投票
	if (date('H:i') == $appconfig['begin_vote']) {
		WechatService::send($appconfig['robot'], '今日投票:  '.$appconfig['url'], ["@all"]);
	}
	// 提醒投票
	if (date('H:i') == $appconfig['notice_vote']) {
		$noticeUser = [];
		if ($noticeUser) {
			WechatService::send($appconfig['robot'], '请抓紧参加今天的投票', $noticeUser);
		}
	}
	// 公布投票结果
	if (date('H:i') == $appconfig['end_vote']) {
		WechatService::send($appconfig['robot'], "投票结果公布:\n speed(1) \n three and one (3)", ["@all"]);
	}
