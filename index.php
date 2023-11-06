<?php

require 'vendor/autoload.php';

use src\User\UserModules;
use src\Cloud\CloudModules;
use src\Games\GamesModules;
use src\Billing\BillingModules;
use src\Support\SupportModules;
use src\Admin\AdminModules;

error_reporting(E_ALL);
ini_set("display_errors", 1);

$status = session_status();
if($status == PHP_SESSION_NONE) { session_start(); } else { }

$router = new src\Router\AltoRouter();
$user = new UserModules($router);
$cloud = new CloudModules($router);
$games = new GamesModules($router);
$billing = new BillingModules($router);
$support = new SupportModules($router);
$admin = new AdminModules($router);

$router->map('GET', '/', 'Errors/redirect', 'manager.redirect');

$match = $router->match();

if(is_array($match)) {
    if (is_callable($match['target'])) {
        call_user_func_array($match['target'], $match['params']);
    } else {
        $params = $match['params'];
        require "public/{$match['target']}.php";
    }
} else {
    require "public/Errors/404.php";
}