<?php

namespace util;

class UserService
{
	public static function getUserByIp($ip, $medoo)
	{
		$data = $medoo->get("dinner_user", "*", ['ip' => $ip]);
		return ($data);
	}

	public static function getVoteByUserIdAndDate($userId, $date, $medoo) {
		$startDate = $date . " 00:00:00";
		$endDate = $date . " 23:59:59";
		$data = $medoo->query("select * from dinner_vote_log where create_time >= '".$startDate."' and create_time <= '".$endDate."' and user_id = ".$userId." order by id desc limit 1")->fetchAll();
		return $data[0];
	}
}
