<?php

namespace util;

class LogService
{
	public static function log($data)
	{
		if (!is_string($data)) {
			$data = json_encode($data);
		}
		file_put_contents('/tmp/dinner-vote-' . date('Ymd') . '.log', '[' . date('Y-m-d H:i:s') . '] ' . $data . PHP_EOL,  FILE_APPEND);
	}
}
