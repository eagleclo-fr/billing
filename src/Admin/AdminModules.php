<?php
namespace src\Admin;
use src\Router\AltoRouter;

class AdminModules
{
    public function __construct(AltoRouter $router)
    {
        $router->map('GET|POST', '/admin', 'admin/index', 'admin.index');
        $router->map('GET|POST', '/admin/users', 'admin/users/list', 'admin.users.list');
        $router->map('GET|POST', '/admin/users/[i:id]', 'admin/users/edit', 'admin.users.edit');
        $router->map('GET', '/admin/support', 'admin/support/list', 'admin.support.list');
        $router->map('GET|POST', '/admin/support/create', 'admin/support/create', 'admin.support.create');
        $router->map('GET|POST', '/admin/support/[i:id]', 'admin/support/support', 'admin.support.support');
    }
}