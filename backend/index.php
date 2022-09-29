<?php
// 直接输出错误
ini_set('display_errors', 'on');
error_reporting(E_ALL & ~E_NOTICE);
// composer 加载
include(__DIR__ . "/vendor/autoload.php");
// 数据库配置加载
$dbconfig = include(__DIR__ . "/config/db.config.php");
$appconfig = include(__DIR__ . "/config/app.config.php");
// 获取数据库连接
use Medoo\Medoo;
use util\UserService;
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
	], JSON_UNESCAPED_UNICODE);
	exit;
}


// 根据ip获取用户
$userInfo = UserService::getUserByIp($_SERVER['REMOTE_ADDR'], $database);
// 找不到用户提示联系系统管理员
if (empty($userInfo)) {
	AjaxResponse('403', [], "IP对应不到用户,请联系管理员");
}

$votes = $database->query("select a.id, a.name, a.name_en from dinner_vote b, dinner_shop a where a.status = 1 and a.id = b.shop_id and b.date = ".date('Ymd'))->fetchAll();
if (empty($votes)) {
	AjaxResponse('500', [], "今日投票还没生成, 请5点之后再来");
}

$userId = $userInfo['id'];
// 首页
if ($route == 'index') {
	// 查看用户今天投票的情况
	$vote = UserService::getVoteByUserIdAndDate($userId, date('Y-m-d'), $database);
	$returnVote = [];
	foreach ($votes as $k => $v) {
		$shopVote  = $database->query("select b.name from dinner_vote_log a, dinner_user b where a.user_id = b.id and a.shop_id = ".$v['id']." and a.create_time > ".date('Y-m-d'))->fetchAll();
		$vo = [
			'id' => $v['id'],
			'name' => $v['name'],
			'name_en' => $v['name_en'],
		];
		if (date('H:i') > $appconfig['end_vote']) {
			$vo['num'] = count($shopVote);
			$vo['username'] = "(".implode(',', array_column($shopVote, 'name')).")";
		}
		$returnVote[] = $vo;
	}
	ajaxResponse(200, [
		'myvote' => $vote['shop_id'] ?? 0,
		'votes' => $returnVote
	]);
	// 查看今天的投票选项
} else if ($route == 'toupiao') {
	if (!isset($_POST['shop_id']) || empty($_POST['shop_id'])) {
		AjaxResponse('500', [], "请选择店铺");
	}
	$shopId = $_POST['shop_id'];
	$hasVote = UserService::getVoteByUserIdAndDate($userId, date('Y-m-d'), $database);
	if ($hasVote) {
		AjaxResponse('500', [], "不能重复投票");
	}
	// 投票
	$flag = VoteService::vote($userId, $shopId, $database);
	if ($flag) {
		WechatService::send($appconfig['robot'], "恭喜! 你已经投票成功", [$userInfo['email']]);
	}
	AjaxResponse('200', [], "投票成功");
} else {
	echo 404;
}
