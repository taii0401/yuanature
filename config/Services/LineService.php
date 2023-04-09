<?php

namespace App\Services;

use GuzzleHttp\Client;

class LineService
{
    public function getLoginBaseUrl($str="test")
    {
        //組成 Line Login Url
        $url = env("LINE_AUTH_BASE_URL")."?";
        $url .= "response_type=code";
        $url .= "&client_id=".env("LINE_CHANNEL_ID");
        $url .= "&redirect_uri=".env("LINE_REDIRECT");
        $url .= "&state=".$str; // 暫時固定方便測試
        $url .= "&scope=profile%20openid%20email";

        return $url;
    }

    public function getLineToken($code)
    {
        $client = new Client();
        $response = $client->request("POST",env("LINE_TOKEN_URL"), [
            "form_params" => [
                "grant_type" => "authorization_code",
                "code" => $code,
                "redirect_uri" => env("LINE_REDIRECT"),
                "client_id" => env("LINE_CHANNEL_ID"),
                "client_secret" => env("LINE_SECRET")
            ]
        ]);

        return json_decode($response->getBody()->getContents(),true);
    }

    public function getUserProfile($token)
    {
        $client = new Client();
        $headers = [
            "Authorization" => "Bearer ".$token,
            "Accept"        => "application/json",
        ];
        $response = $client->request("GET",env("LINE_USER_PROFILE_URL"), [
            "headers" => $headers
        ]);

        return json_decode($response->getBody()->getContents(),true);
    }
}