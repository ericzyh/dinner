<?php

/**
 * 发送企业微信的service
 *
 **/

namespace util;

use GuzzleHttp;
use Exception;

class WechatService
{

    public static function send($token, $msg, $at = [])
    {
        try {
            $client = new GuzzleHttp\Client([
                'headers' => ['Content-Type' => 'application/json'],
                'timeout'  => 2,
            ]);
            $resp = $client->post('https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=' . $token, [
                'body' => json_encode([
                    "msgtype" => "text",
                    "text" => [
                        "content" => $msg,
                        "mentioned_list" => $at,
                    ]
                ])
            ]);
        } catch (Exception $e) {
        }
    }
}
