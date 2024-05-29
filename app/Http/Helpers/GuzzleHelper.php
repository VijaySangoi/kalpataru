<?php

namespace App\Http\Helpers;

use Exception;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GuzzleHelper
{
    public static function call(String $url, String $method, array $data = []) 
    {
        $client = new \GuzzleHttp\Client();
        $res = $client->request($method,$url,$data);
        return [$res->getBody(),$res->getStatusCode()];
    }
}