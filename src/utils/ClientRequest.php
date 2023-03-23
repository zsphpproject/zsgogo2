<?php

namespace Zsgogo\utils;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zsgogo\constant\ErrorNums;
use Zsgogo\exception\AppException;

class ClientRequest {

    /**
     * 发送请求
     * @param string $baseUrl  接口跟路径
     * @param string $path     接口uri
     * @param string $method
     * @param array $param    参数
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function send(string $baseUrl,string $path = "",string $method = "post",array $param = []): mixed {
        $client = new Client([
            "base_uri" => $baseUrl,
            "timeout" => 10.0
        ]);

        $option[RequestOptions::JSON] = [];
        if (!empty($param)) $option[RequestOptions::JSON] = $param;

        Log::get("guzzle_request")->info(json_encode($option,JSON_UNESCAPED_UNICODE));

        try {
            $response = $client->request($method, $path, $option);
            $res = json_decode($response->getBody()->getContents(),true);
            Log::get("guzzle_response")->info(json_encode($res,JSON_UNESCAPED_UNICODE));

            if ($response->getStatusCode() == 200){
                if ($res["code"] != 0) {
                    Log::get("guzzle_response")->info("{$path}请求错误:" . $res["msg"]);
                    throw new AppException(ErrorNums::GUZZLE_REQUEST_FAIL,$res["msg"]);
                }
                return $res["data"];
            }else{
                $message = $response->getBody()->getContents();
                Log::get("guzzle_response")->info("{$path}请求失败:" . $message);
                throw new AppException(ErrorNums::GUZZLE_REQUEST_FAIL,$message);
            }
        }catch (GuzzleException $exception){
            $error = $exception->getMessage();
            Log::get("guzzle_response")->info("{$path}求失败:" . $error);
            throw new AppException(ErrorNums::GUZZLE_REQUEST_FAIL,$error);
        }
    }



}