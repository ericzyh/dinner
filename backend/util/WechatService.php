<?php
/**
 * 发送企业微信的service
 *
 **/
namespace util;

use GuzzleHttp;

class WechatService 
{

    public static function send($token, $msg) {
        try {
            $client = new GuzzleHttp\Client([
                'headers' => [ 'Content-Type' => 'application/json' ],
                'timeout'  => 2,
            ]);
            $resp = $client->post('https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key='.$token, [
                'body' => json_encode([
                    "msgtype"=>"text",
                    "text" => [
                        "content"=>$msg
                    ]
                ])
            ]);
        } catch (Exception $e) {
        }
    }
}
