<?php

namespace util;

class VoteService
{
	public static function choose($medoo)
	{
		// 如果已经生成了投票直接返回
		$vote = $medoo->query("select * from dinner_vote where date = ".date('Ymd'))->fetchAll();
		if ($vote) {
			return true;
		}
		// 如果没有生成就生成
		$votes = $medoo->query("SELECT a.* FROM dinner_shop a LEFT join dinner_vote b on a.id = b.shop_id and b.status = 1 AND b.create_time > '".date('Y-m-d', strtotime("this week"))."' WHERE b.id IS NULL AND a.status = 1 order BY RAND() LIMIT 3")->fetchAll();
		foreach ($votes as $vote) {
			$medoo->insert("dinner_vote", [
				"shop_id" => $vote['id'],
				"date" => date('Ymd')
			]);
		}
	}

	public static function vote($userId, $shopId, $medoo)
	{
		return $medoo->insert("dinner_vote_log", [
			"user_id" => $userId,
			"shop_id" => $shopId
		]);
	}
}
