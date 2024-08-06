<?php

namespace thinks\rule;

class LoadRoutes
{
    public static function loadRoutes()
    {
        $routePath = __DIR__ . '/routes.php';
        if (file_exists($routePath)) {
            require_once $routePath;
        }
    }
}
