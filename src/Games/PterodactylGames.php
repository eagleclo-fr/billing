<?php
namespace src\Games;

use src\User\User;
use src\Database\Database;
use src\Service\Database\pterodactylTable;
use src\Games\PterodactylConnector;

class PterodactylGames
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->connector = new PterodactylConnector();
        $this->user = new User();
    }

    public function createServer(int $userid, $games, $nestPterodactyl, $eggPterodactyl, $docker_imagePterodactyl, $startupPterodactyl, $memoryPterodactyl, $swapPterodactyl, $diskPterodactyl, $cpuPterodactyl, $databasesPterodactyl, $allocationsPterodactyl, $backupsPterodactyl, $locationsPterodactyl, $start_on_completion)
    {
        $this->user->userInfo($userid);

        $reqMail = $this->connector->requestApi("GET", [], "users?filter%5Bemail%5D=" . $this->user->mail, 'application');
        $data = [
            'username'   => 'account'.$userid.'',
            'email'      => $this->user->mail,
            'first_name' => $this->user->firstname,
            'last_name'  => $this->user->lastname,
            'password'   => 'pass-'.$userid.'$erf'.rand(1, 99999).'',
            'language'   => 'en'

        ];

        if($reqMail['meta']['pagination']['total'] == 0){
            $this->create = $this->connector->requestApi('POST', $data, "users", 'application');

            $insert = $this->pdo -> prepare('INSERT INTO `games_account_pterodactyl`(`userid`, `email`, `password`, `date`) VALUES (?,?,?,NOW())');
            $insert->execute(array($userid, $data['email'], $data['password']));

        }elseif ($reqMail['meta']['pagination']['total'] == 1) {
            $reqAccount = $this->pdo -> prepare('SELECT * FROM games_account_pterodactyl WHERE email = ?');
            $reqAccount->execute(array($this->user->mail));
            $infoAccount = $reqAccount->fetch();
            $this->mailAccount = $infoAccount['email'];
            $this->passwordAccount = $infoAccount['password'];
        }

        $reqMail2 = $this->connector->requestApi('GET', [], "users?filter%5Bemail%5D=" . $this->user->mail, 'application');

        if($reqMail2['meta']['pagination']['total'] == 1){

            if($games == "minecraft") {
                $this->serverData = array(
                    'name' => 'Minecraft',
                    'external_id' => 'interne-'.$userid.''.rand(1, 99999).'',
                    'user' => (int)$reqMail2['data'][0]['attributes']['id'],
                    'nest' => $nestPterodactyl,
                    'egg' => $eggPterodactyl,
                    'docker_image' => '' . $docker_imagePterodactyl . '',
                    'startup' => '' . $startupPterodactyl . '',
                    'limits' => array(
                        'memory' => $memoryPterodactyl,
                        'swap' => $swapPterodactyl,
                        'disk' => $diskPterodactyl,
                        'io' => 500,
                        'cpu' => $cpuPterodactyl
                    ),
                    'feature_limits' => array(
                        'databases' => $databasesPterodactyl,
                        'allocations' => $allocationsPterodactyl,
                        'backups' => $backupsPterodactyl,
                    ),
                    'environment' => array(
                        'SERVER_JARFILE' => 'server.jar',
                        'DL_VERSION' => 'latest',
                    ),
                    'deploy' => array(
                        'locations' => [$locationsPterodactyl],
                        'dedicated_ip' => false,
                        'port_range' => [],
                    ),
                    'start_on_completion' => true
                );
            } else if($games == "gmod") {
                $this->serverData = array(
                    'name' => 'mc',
                    'external_id' => 'interne-'.$userid.''.rand(1, 99999).'',
                    'user' => (int)$reqMail2['data'][0]['attributes']['id'],
                    'nest' => $nestPterodactyl,
                    'egg' => $eggPterodactyl,
                    'docker_image' => '' . $docker_imagePterodactyl . '',
                    'startup' => '' . $startupPterodactyl . '',
                    'limits' => array(
                        'memory' => $memoryPterodactyl,
                        'swap' => $swapPterodactyl,
                        'disk' => $diskPterodactyl,
                        'io' => 500,
                        'cpu' => $cpuPterodactyl
                    ),
                    'feature_limits' => array(
                        'databases' => $databasesPterodactyl,
                        'allocations' => $allocationsPterodactyl,
                        'backups' => $backupsPterodactyl,
                    ),
                    'environment' => array(
                        'SRCDS_MAP' => 'gm_flatgrass',
                        'STEAM_ACC' => '',
                        'SRCDS_APPID' => "4020",
                        'WORKSHOP_ID' => "",
                        'GAMEMODE' => "sandbox",
                        'MAX_PLAYERS' => "32",
                        'TICKRATE' => "22",
                    ),
                    'deploy' => array(
                        'locations' => [$locationsPterodactyl],
                        'dedicated_ip' => false,
                        'port_range' => [],
                    ),
                    'start_on_completion' => ''.$start_on_completion.''
                );
            } else if($games == "gmod") {
                $this->serverData = array(
                    'name' => 'mc',
                    'external_id' => 'interne-'.$userid.''.rand(1, 99999).'',
                    'user' => (int)$reqMail2['data'][0]['attributes']['id'],
                    'nest' => $nestPterodactyl,
                    'egg' => $eggPterodactyl,
                    'docker_image' => '' . $docker_imagePterodactyl . '',
                    'startup' => '' . $startupPterodactyl . '',
                    'limits' => array(
                        'memory' => $memoryPterodactyl,
                        'swap' => $swapPterodactyl,
                        'disk' => $diskPterodactyl,
                        'io' => 500,
                        'cpu' => $cpuPterodactyl
                    ),
                    'feature_limits' => array(
                        'databases' => $databasesPterodactyl,
                        'allocations' => $allocationsPterodactyl,
                        'backups' => $backupsPterodactyl,
                    ),
                    'environment' => array(
                        'FIVEM_LICENSE' => '',
                        'MAX_PLAYERS' => '48',
                        'SERVER_HOSTNAME' => "Mon serveur GTARP!",
                        'FIVEM_VERSION' => "latest",
                        'DOWNLOAD_URL' => "",
                        'STEAM_WEBAPIKEY' => "none",
                        'TXADMIN_PORT' => "40120",
                        'TXADMIN_ENABLE' => "0",
                    ),
                    'deploy' => array(
                        'locations' => [$locationsPterodactyl],
                        'dedicated_ip' => false,
                        'port_range' => [],
                    ),
                    'start_on_completion' => ''.$start_on_completion.''
                );
            }

            $this->create_srv = $this->connector->requestApi('POST', $this->serverData, "servers", 'application');
            var_dump($this->create_srv);
            $this->internal_id = (string)$this->create_srv['attributes']['id'];
            $this->external_id = (string)$this->create_srv['attributes']['identifier'];
        }
    }

    public function getResources(string $external_id){
        $this->get = $this->connector->requestApi('GET', [], 'servers/'.$external_id.'/resources', 'client');
        if(isset($this->get['attributes']['current_state']) == null){
            $this->status = null;
        } else {
            $this->status = $this->get['attributes']['current_state'];
        }
    }

    public function getActions(string $external_id, string $action){
        $this->get = $this->connector->requestApi('POST', ["signal" => $action], 'servers/'.$external_id.'/power', 'client');
    }

    public function getRebuild(string $external_id, string $action){
        $this->get = $this->connector->requestApi('POST', [], 'servers/'.$external_id.'/settings/reinstall', 'client');
    }

    public function getWebsocket(string $external_id){
        $this->get = $this->connector->requestApi('GET', [], 'servers/'.$external_id.'/websocket', 'client');

        $this->token = $this->get['data']['token'];
        $this->socket = $this->get['data']['socket'];
    }
}