<?php
namespace src\Cloud;
use src\Router\AltoRouter;

class CloudModules
{
    public function __construct(AltoRouter $router)
    {
        $router->map('GET|POST', '/cloud', 'manager/cloud/list', 'manager.cloud.list');
        $router->map('GET|POST', '/cloud/deploy', 'manager/cloud/deploy', 'manager.cloud.deploy');

        $router->map('GET|POST', '/cloud/servers/[*:idservice]/overview', 'manager/cloud/PVE/gestion', 'manager.cloud.pve.gestion');
        $router->map('GET|POST', '/cloud/servers/[*:idservice]/snapshots', 'manager/cloud/PVE/snapshots', 'manager.cloud.pve.snapshots');
        $router->map('GET|POST', '/cloud/servers/[*:idservice]/network', 'manager/cloud/PVE/network', 'manager.cloud.pve.network');
        $router->map('GET|POST', '/cloud/servers/[*:idservice]/power', 'manager/cloud/PVE/power', 'manager.cloud.pve.power');
        $router->map('GET|POST', '/cloud/servers/[*:idservice]/rebuild', 'manager/cloud/PVE/rebuild', 'manager.cloud.pve.rebuild');
        $router->map('GET|POST', '/cloud/servers/[*:idservice]/identifiers', 'manager/cloud/PVE/identifiers', 'manager.cloud.pve.identifiers');

        $router->map('GET|POST', '/cloud/cron', 'manager/cloud/PVE/cron', 'manager.cloud.pve.cron');
    }
}