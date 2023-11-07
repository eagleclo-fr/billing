<?php
namespace src\User;
use src\Router\AltoRouter;

class UserModules
{
    public function __construct(AltoRouter $router)
    {
        $router->map('GET|POST', '/login', 'manager/auth/login', 'manager.login');
        $router->map('GET|POST', '/register', 'manager/auth/register', 'manager.register');
        $router->map('GET', '/logout', 'manager/auth/logout', 'manager.logout');

        $router->map('GET|POST', '/manager', 'manager/manager/index', 'manager.manager');
        $router->map('GET|POST', '/account', 'manager/manager/account', 'manager.account');
    }
}