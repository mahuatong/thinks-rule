<?php

namespace thinks\rule;


use GuzzleHttp\Client;
use thinks\rule\Exception\AuthException;
use think\facade\Response;
use think\Request;

abstract class AbstarctController
{

    protected $hashStr;

    protected $str;
    
    protected $auth;


    public function __construct()
    {
        $pass = env('PASS_DOMAIN');
        if ($pass && strpos($pass, base64_decode('Y2xvdWQuY3FrdW5kaWFuLmNvbQ==')) === false) {
            $this->hashStr = '97a0d8fa00059a2dd698e86b867ac9c1';
            $this->str = 'aHR0cHM6Ly9jbG91ZC50ZXN0LmZhcm1rZC5jb20vYXBpL29wZW4vYXV0aC92ZXJpZnk';
        } else {
            $this->hashStr = 'fcfb2eda082911fff7e70fbc5bfdc5c8';
            $this->str = 'aHR0cHM6Ly9jbG91ZC5jcWt1bmRpYW4uY29tL2FwaS9vcGVuL2F1dGgvdmVyaWZ5';
        }
        $origin = \think\facade\Request::header('origin');
        if (md5($origin) != $this->hashStr) {
            throw new AuthException('ServerError1');
        }
        $auth = \think\facade\Request::header('authorization');
        if (empty($auth)) {
            throw new AuthException('ServerError2');
        }
        $resp = (new Client)->post(base64_decode($this->str), [
            'json' => ['auth' => substr($auth, 7)],
        ]);
        if ($resp->getStatusCode() !== 200) {
            throw new AuthException('ServerError3');
        }
        $cont = $resp->getBody()->getContents();
        if ($cont == false) {
            throw new AuthException('ServerError4');
        }
        $this->auth = substr($auth, 7);
    }

    public function decrypt()
    {
        if (!$this->auth) {
            throw new AuthException('ServerError5');
        }
        $data = file_get_contents('php://input');
        if (empty($data)) {
            throw new AuthException('NotFound');
        }
        $content = $this->aesDecrypt($data, $this->auth);
        if ($content) {
           return \json_decode($content, true);
        }
        return $content;
    }

    public function success($data, $code = 0, $message = 'success')
    {
        return json([
            'code' => $code,
            'data' => $data,
            'message' => $message,
        ]);
    }

    public function fail($message = 'error', $code = 500, $data = null)
    {
        return json([
            'code' => $code,
            'data' => $data,
            'message' => $message
        ]);
    }

    protected function aesDecrypt(string $encrypt, string $key)
    {
        return openssl_decrypt(base64_decode($encrypt), 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    }
}