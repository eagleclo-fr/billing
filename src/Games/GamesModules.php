<?php
namespace src\Games;
use src\Router\AltoRouter;

class GamesModules
{
    public function __construct(AltoRouter $router)
    {
        $router->map('GET|POST', '/games', 'manager/games/list', 'manager.games.list');
        $router->map('GET|POST', '/games/cron', 'manager/games/Pterodactyl/cron', 'manager.games.pterodactyl.cron');

        $router->map('GET|POST', '/games/servers/[*:idservice]/overview', 'manager/games/Pterodactyl/gestion', 'manager.games.pterodactyl.gestion');
        $router->map('GET|POST', '/games/servers/[*:idservice]/identifiers', 'manager/games/Pterodactyl/identifiers', 'manager.games.pterodactyl.identifiers');
        $router->map('GET|POST', '/games/servers/[*:idservice]/rebuild', 'manager/games/Pterodactyl/rebuild', 'manager.games.pterodactyl.rebuild');
    }
}