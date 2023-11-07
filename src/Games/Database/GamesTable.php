<?php
namespace src\Games\Database;

use src\Database\Database;

class GamesTable
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
    }

    public function getAllGamesTable(int $userid)
    {
        $this->select = $this->pdo->prepare('SELECT * FROM `games` WHERE userid = :userid ORDER BY id DESC');
        $this->select->bindValue(':userid', $userid, $this->pdo::PARAM_STR);
        $this->select->execute();
        $this->exist = $this->select->rowCount();
        while ($this->result = $this->select->fetch(\PDO::FETCH_ASSOC)) {

            $this->getGamesTable($this->result['idservice']);

            echo '<div class="col-3">
                    <div class="card">
                    <div class="card-body">
                        <center><h4 class="card-title">'.$this->idservice.'</h4></center>
                        <center><p>
                        Status : '.$this->getStatus.'
                        <br>
                        Expiration : <span class="badge bg-dark">'.date('d/m/Y', strtotime($this->expiry)).'</span>
                        </p></center>
                        <center><p class="card-text">
                            <a href="/games/servers/'.$this->idservice.'/overview" class="btn btn-primary"><i class="bi bi-gear"></i> Gestion</a> 
                            <a href="#" class="btn btn-primary"><i class="bi bi-life-preserver"></i> Support</a>
                        </p></center>
                    </div>
                </div>
            </div>';

        }
    }

    public function getGamesTable(string $id_service)
    {
        $this->get = $this->pdo->prepare('SELECT * FROM `games` WHERE idservice = :idservice');
        $this->get->bindValue(':idservice', $id_service, $this->pdo::PARAM_STR);
        $this->get->execute();
        $this->result = $this->get->fetch();
        $this->checkExist = $this->get->rowCount();

        if ($this->checkExist == 1) {
            $this->userid = $this->result['userid'];
            $this->idservice = $this->result['idservice'];
            $this->offer = $this->result['offer'];
            $this->status = $this->result['status'];
            $this->firstpaymentamount = $this->result['firstpaymentamount'];
            $this->price = $this->result['price'];
            $this->expiry = $this->result['expiry'];
            $this->name = $this->result['name'];
            $this->date_created = $this->result['date_created'];
            $this->date_updated = $this->result['date_updated'];

            if($this->status == "active"){
                $this->getStatus = '<span class="badge bg-success">Actif</span>';
            } else if($this->status == "suspend"){
                $this->getStatus = '<span class="badge bg-warning">Suspendu</span>';
            }
        }
    }

    public function getGamesServerTable(string $id_service)
    {
        $this->get = $this->pdo->prepare('SELECT * FROM `games_servers` WHERE idservice = :id_service');
        $this->get->bindValue(':id_service', $id_service, $this->pdo::PARAM_STR);
        $this->get->execute();
        $this->result = $this->get->fetch();
        $this->checkExist = $this->get->rowCount();

        if ($this->checkExist == 1) {
            $this->userid = $this->result['userid'];
            $this->id_service = $this->result['idservice'];
            $this->serverGames = $this->result['server'];
            $this->statusGames = $this->result['status'];
            $this->internal_id = $this->result['internal_id'];
            $this->external_id = $this->result['external_id'];
            $this->plan_nameGames = $this->result['plan_name'];
            $this->spaceGames = $this->result['space'];
            $this->ramGames = $this->result['ram'];
            $this->coresGames = $this->result['cores'];
            $this->hdd_modelGames = $this->result['hdd_model'];
            $this->date_created = $this->result['date_created'];
            $this->date_updated = $this->result['date_updated'];
        } else {
            echo 'Erreur de synchronisation';
            exit();
        }
    }

    public function getAllTasksTable(string $id_service){
        $this->select = $this->pdo->prepare('SELECT * FROM `games_servers_tasks` WHERE idservice = :idservice ORDER BY id DESC LIMIT 10');
        $this->select->bindValue(':idservice', $id_service, $this->pdo::PARAM_STR);
        $this->select->execute();
        $this->exist = $this->select->rowCount();
        while ($this->result = $this->select->fetch(\PDO::FETCH_ASSOC)) {

            $this->getTask($this->result['id']);

            echo '<small>'.$this->getStatus.' '.$this->getAction.' - '.$this->date_created.'</small><br>';

        }
    }

    public function addTask(string $idservice, int $userid, string $actions, string $status){
        $this->insert = $this->pdo->prepare('INSERT INTO `games_servers_tasks` SET idservice = :idservice, userid = :userid, actions = :actions, status = :status, date_created = :date_created');
        $this->insert->bindValue(':idservice', $idservice, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':userid', $userid, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':actions', $actions, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':status', $status, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':date_created', date('d-m-Y H:i:s'), $this->pdo::PARAM_STR);
        $this->insert->execute();
    }

    public function getTask(int $id){
        $this->get = $this->pdo->prepare('SELECT * FROM `games_servers_tasks` WHERE id = :id');
        $this->get->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $this->get->execute();
        $this->result = $this->get->fetch();
        $this->checkExist = $this->get->rowCount();

        if ($this->checkExist == 1) {
            $this->userid = $this->result['userid'];
            $this->idservice = $this->result['idservice'];
            $this->actions = $this->result['actions'];
            $this->status = $this->result['status'];
            $this->date_created = date('d/m/Y H:i', strtotime($this->result['date_created']));

            if($this->actions == "start"){
                $this->getAction = '[<i class="bi bi-play-fill"></i>] Démarrage du serveur';
            } else if($this->actions == "stop") {
                $this->getAction = '[<i class="bi bi-x-octagon-fill"></i>] Extinction du serveur';
            } else if($this->actions == "shutdown") {
                $this->getAction = '[<i class="bi bi-x-octagon-fill"></i>] Kill du serveur';
            } else if($this->actions == "rollback_snapshot") {
                $this->getAction = '[<i class="bi bi-cloud-download-fill"></i>] Restauration d\'une Snapshot';
            } else if($this->actions == "delete_snapshot") {
                $this->getAction = '[<i class="bi bi-eraser-fill"></i>] Suppression d\'une Snapshot';
            } else if($this->actions == "create_snapshot") {
                $this->getAction = '[<i class="bi bi-plus-circle-fill"></i>] Création d\'une Snapshot';
            } else if($this->actions == "reinstall") {
                $this->getAction = '[<i class="bi bi-download"></i>] Réinstallation du serveur';
            }

            if($this->status == "success"){
                $this->getStatus = '<span class="badge bg-success"><i class="bi bi-check-circle-fill"></i></span>';
            } else if($this->status == "error"){
                $this->getStatus = '<span class="badge bg-danger"><i class="bi bi-x-octagon-fill"></i></span>';
            }
        }
    }

    public function getAccountPterodactylTable(int $userid)
    {
        $this->get = $this->pdo->prepare('SELECT * FROM `games_account_pterodactyl` WHERE userid = :userid');
        $this->get->bindValue(':userid', $userid, $this->pdo::PARAM_INT);
        $this->get->execute();
        $this->result = $this->get->fetch();
        $this->checkExist = $this->get->rowCount();

        if ($this->checkExist == 1) {
            $this->userid = $this->result['userid'];
            $this->emailPterodactyl = $this->result['email'];
            $this->passwordPterodactyl = $this->result['password'];
            $this->date_created = date('d/m/Y H:i', strtotime($this->result['date']));
        } else {
            $this->emailPterodactyl = 'undefined';
            $this->passwordPterodactyl = 'undefined';
            $this->date_created = '00/00/0000';
        }
    }

    public function getGamesOfferTable(int $offer)
    {
        $this->get = $this->pdo->prepare('SELECT * FROM `games_offer` WHERE id = :id');
        $this->get->bindValue(':id', $offer, $this->pdo::PARAM_INT);
        $this->get->execute();
        $this->result = $this->get->fetch();
        $this->checkExist = $this->get->rowCount();

        if ($this->checkExist == 1) {
            $this->swap = $this->result['swap'];
            $this->type_games = $this->result['type_games'];
            $this->nestPterodactyl = $this->result['nestPterodactyl'];
            $this->eggPterodactyl = $this->result['eggPterodactyl'];
            $this->docker_imagePterodactyl = $this->result['docker_imagePterodactyl'];
            $this->startupPterodactyl = $this->result['startupPterodactyl'];
            $this->databasesPterodactyl = $this->result['databasesPterodactyl'];
            $this->allocationsPterodactyl = $this->result['allocationsPterodactyl'];
            $this->backupsPterodactyl = $this->result['backupsPterodactyl'];
            $this->start_on_completion = $this->result['start_on_completion'];
        }
    }

    public function updateInstall(string $id_service, string $internal_id, string $external_id){
        $this->update = $this->pdo->prepare('UPDATE `games_servers` SET status = :status, internal_id = :internal_id, external_id = :external_id WHERE idservice = :idservice');
        $this->update->bindValue(':status', 'created', $this->pdo::PARAM_STR);
        $this->update->bindValue(':internal_id', $internal_id, $this->pdo::PARAM_STR);
        $this->update->bindValue(':external_id', $external_id, $this->pdo::PARAM_STR);
        $this->update->bindValue(':idservice', $id_service, $this->pdo::PARAM_STR);
        $this->update->execute();
    }

}
