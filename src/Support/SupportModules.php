<?php
namespace src\Support;
use src\Router\AltoRouter;

class SupportModules
{
    public function __construct(AltoRouter $router)
    {
        $router->map('GET', '/support', 'manager/support/list', 'manager.support.list');
        $router->map('GET|POST', '/support/create', 'manager/support/create', 'manager.support.create');
        $router->map('GET|POST', '/support/[i:id]', 'manager/support/support', 'manager.support.support');
    }
}