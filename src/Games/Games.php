<?php
namespace src\Games;

use src\Database\Database;
use src\Games\Database\GamesTable;
use src\Billing\Database\OfferTable;
use src\Router\RouterHelper;
use src\Helper\FlashService;
use src\Games\PterodactylGames;

class Games
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->gamesTable = new GamesTable();
        $this->router = new RouterHelper();
        $this->offerTable = new OfferTable();
        $this->flash = new FlashService();
        $this->pterodactylGames = new PterodactylGames();
    }

    public function getAllGames(int $userid)
    {
        return $this->gamesTable->getAllGamesTable($userid);
    }

    public function getGames(int $userid, string $id_service){
        $this->gamesTable->getGamesTable($id_service);
        $this->getGameServer($userid, $id_service);
        $this->pterodactylGames->getResources($this->gamesTable->external_id);
        $this->statusGames = $this->pterodactylGames->status;

        $this->userid = $this->gamesTable->userid;
        $this->idservice = $this->gamesTable->idservice;
        $this->offer = $this->gamesTable->offer;
        $this->status = $this->gamesTable->status;
        $this->getStatus = $this->gamesTable->getStatus;
        $this->firstpaymentamount = $this->gamesTable->firstpaymentamount;
        $this->price = $this->gamesTable->price;
        $this->expiry = $this->gamesTable->expiry;
        $this->name = $this->gamesTable->name;
        $this->date_created = $this->gamesTable->date_created;
        $this->date_updated = $this->gamesTable->date_updated;

        if ($this->statusGames == "running") {
            $this->getstatusGames = '<button type="button" class="btn btn-success"><i class="bi bi-check-circle"></i></button>';
            $this->getstatusGamesWrite = 'En ligne';
        } else if ($this->statusGames == "starting") {
            $this->getstatusGames = '<button type="button" class="btn btn-warning"><i class="bi bi-hourglass-top"></i></button>';
            $this->getstatusGamesWrite = 'Démarrage en cours';
        } else if ($this->statusGames == "offline") {
            $this->getstatusGames = '<button type="button" class="btn btn-danger"><i class="bi bi-x-octagon-fill"></i></button>';
            $this->getstatusGamesWrite = 'Hors ligne';
        } else {
            $this->getstatusGames = '<button type="button" class="btn btn-danger"><i class="bi bi-x-octagon-fill"></i></button>';
            $this->getstatusGamesWrite = 'Réinstallation';
        }
    }

    public function getWebsocket(string $external_id){
        $this->pterodactylGames->getWebsocket($external_id);
    }

    public function getAccountPterodactyl(int $userid){
        $this->gamesTable->getAccountPterodactylTable($userid);
        $this->emailPterodactyl = $this->gamesTable->emailPterodactyl;
        $this->passwordPterodactyl = $this->gamesTable->passwordPterodactyl;
    }

    public function getActions(string $id_service, int $userid, string $external_id, string $action){
        $this->pterodactylGames->getActions($external_id, $action);
        $this->gamesTable->addTask($id_service, $userid, $action, 'success');
        $this->flash->setFlash('Votre action de '.$action.' sur le service <strong>'.$id_service.'</strong> a été effectué avec succès !', 'success');
        echo '<meta http-equiv="refresh" content="4; URL=/games/servers/'.$id_service.'/overview">';
    }

    public function getRebuild(string $id_service, int $userid, string $external_id, string $action){
        $this->pterodactylGames->getRebuild($external_id, $action);
        $this->gamesTable->addTask($id_service, $userid, 'reinstall', 'success');
        $this->flash->setFlash('Votre action de réinstallation sur le service <strong>'.$id_service.'</strong> a été effectué avec succès !', 'success');
        echo '<meta http-equiv="refresh" content="4; URL=/games/servers/'.$id_service.'/overview">';
    }

    public function getGameServer(int $userid, string $id_service){
        $this->gamesTable->getGamesServerTable($id_service);

        if($userid == $this->gamesTable->userid){

            $this->id_service = $this->gamesTable->id_service;
            $this->statusGames = $this->gamesTable->statusGames;
            $this->serverGames = $this->gamesTable->serverGames;
            $this->internal_id = $this->gamesTable->internal_id;
            $this->external_id = $this->gamesTable->external_id;
            $this->plan_nameGames = $this->gamesTable->plan_nameGames;
            $this->spaceGames = $this->gamesTable->spaceGames;
            $this->ramGames = $this->gamesTable->ramGames;
            $this->coresGames = $this->gamesTable->coresGames;
            $this->hdd_modelGames = $this->gamesTable->hdd_modelGames;

        } else {
            $this->router->redirect('/games');
        }
    }

    public function getTasks(string $id_service){
        return $this->gamesTable->getAllTasksTable($id_service);
    }

}
