<?php
// 直接输出错误
ini_set('display_errors', 'on');
error_reporting(E_ALL);
// composer 加载
include(__DIR__ . "/vendor/autoload.php");
// 数据库配置加载
$dbconfig = include(__DIR__ . "/config/db.config.php");
// 应用配置加载
$appconfig = include(__DIR__ . "/config/app.config.php");
// 获取数据库连接
use Medoo\Medoo;
use util\VoteService;
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
function ajaxResponse($code = '200', $data = [], $msg = '')
{
	echo json_encode([
		'code' => $code,
		'data' => $data,
		'msg' => $msg
	]);
}

// 开始投票
if (date('H:i') == $appconfig['begin_vote']) {
	VoteService::choose($database);
	WechatService::send($appconfig['robot'], '今日晚餐投票已生成, 请做出你的选择:  ' . $appconfig['url'], ["@all"]);
}
// 提醒投票
if (in_array(date('H:i'), $appconfig['notice_vote'])) {
	$data = $database->query("SELECT a.* FROM dinner_user a left join dinner_vote_log b on a.id = b.user_id  AND b.create_time > '".date('Y-m-d')." 00:00:00' WHERE a.status = 1 AND b.id IS null")->fetchAll();
	if ($data) {
		$noticeUser = [];
		foreach ($data as $item) {
			$noticeUser[] = $item['email'];
		}
		if ($noticeUser) {
			WechatService::send($appconfig['robot'], '投票即将结束, 请抓紧参加今天的投票 '.$appconfig['url'], $noticeUser);
		}
	}
}
// 公布投票结果
if (date('H:i') == $appconfig['end_vote']) {
	$data = $database->query("SELECT a.shop_id, b.name, b.name_en, COUNT(*) as cnt FROM dinner_vote_log a, dinner_shop b WHERE a.shop_id = b.id AND a.create_time > '".date('Y-m-d')." 00:00:00' GROUP BY shop_id order by cnt desc")->fetchAll();
	if (empty($data)) {
		WechatService::send($appconfig['robot'], "投票结果公布: 没人投票", ["@all"]);
	} else {
		$winer = 0;
		if ($data[0]['cnt'] == $data[1]['cnt']) {
			$winer = rand(0, 1);
		}
		WechatService::send($appconfig['robot'], "投票结果公布:\n ".$data[$winer]['name_en']." 获得".$data[$winer]['cnt']."票, GOGOGO! \n 详情请查看 ".$appconfig['url'], ["@all"]);
		$database->query("update dinner_vote set status = 1 where date = ".date('Ymd'). " and shop_id = " .  $data[$winer]['shop_id']);
	}
}
