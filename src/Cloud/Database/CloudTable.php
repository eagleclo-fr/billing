<?php
namespace src\Cloud\Database;

use src\Database\Database;

class CloudTable
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
    }

    public function getAllCloudTable(int $userid)
    {
        $this->select = $this->pdo->prepare('SELECT * FROM `cloud` WHERE userid = :userid ORDER BY id DESC');
        $this->select->bindValue(':userid', $userid, $this->pdo::PARAM_STR);
        $this->select->execute();
        $this->exist = $this->select->rowCount();
        while ($this->result = $this->select->fetch(\PDO::FETCH_ASSOC)) {

            $this->getCloudTable($this->result['idservice']);

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
                            <a href="/cloud/servers/'.$this->idservice.'/overview" class="btn btn-primary"><i class="bi bi-gear"></i> Gestion</a> 
                            <a href="#" class="btn btn-primary"><i class="bi bi-life-preserver"></i> Support</a>
                        </p></center>
                    </div>
                </div><!-- End Card with titles, buttons, and links -->
            </div>';

        }
    }

    public function getAllTasksTable(string $id_service){
        $this->select = $this->pdo->prepare('SELECT * FROM `cloud_servers_tasks` WHERE idservice = :idservice ORDER BY id DESC LIMIT 10');
        $this->select->bindValue(':idservice', $id_service, $this->pdo::PARAM_STR);
        $this->select->execute();
        $this->exist = $this->select->rowCount();
        while ($this->result = $this->select->fetch(\PDO::FETCH_ASSOC)) {

            $this->getTask($this->result['id']);

            echo '<small>'.$this->getStatus.' '.$this->getAction.' - '.$this->date_created.'</small><br>';

        }
    }

    public function addTask(string $idservice, int $userid, string $actions, string $status){
        $this->insert = $this->pdo->prepare('INSERT INTO `cloud_servers_tasks` SET idservice = :idservice, userid = :userid, actions = :actions, status = :status, date_created = :date_created');
        $this->insert->bindValue(':idservice', $idservice, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':userid', $userid, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':actions', $actions, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':status', $status, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':date_created', date('d-m-Y H:i:s'), $this->pdo::PARAM_STR);
        $this->insert->execute();
    }

    public function getTask(int $id){
        $this->get = $this->pdo->prepare('SELECT * FROM `cloud_servers_tasks` WHERE id = :id');
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

    public function getCloudTable(string $id_service)
    {
        $this->get = $this->pdo->prepare('SELECT * FROM `cloud` WHERE idservice = :idservice');
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

    public function getCloudServerTable(string $id_service)
    {
        $this->get = $this->pdo->prepare('SELECT * FROM `cloud_servers` WHERE idservice = :id_service');
        $this->get->bindValue(':id_service', $id_service, $this->pdo::PARAM_STR);
        $this->get->execute();
        $this->result = $this->get->fetch();
        $this->checkExist = $this->get->rowCount();

        if ($this->checkExist == 1) {
            $this->userid = $this->result['userid'];
            $this->id_service = $this->result['idservice'];
            $this->serverVM = $this->result['server'];
            $this->statusVM = $this->result['status'];
            $this->vm_id = $this->result['vm_id'];
            $this->address_ipVM = $this->result['address_ip'];
            $this->plan_nameVM = $this->result['plan_name'];
            $this->spaceVM = $this->result['space'];
            $this->ramVM = $this->result['ram'];
            $this->coresVM = $this->result['cores'];
            $this->hdd_modelVM = $this->result['hdd_model'];
            $this->keyrootVM = $this->result['password'];
            $this->date_created = $this->result['date_created'];
            $this->date_updated = $this->result['date_updated'];
        } else {
            echo 'Erreur de synchronisation';
            exit();
        }
    }

    public function getPVETable(int $id)
    {
        $this->get = $this->pdo->prepare('SELECT * FROM `cloud_pve` WHERE id = :id');
        $this->get->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $this->get->execute();
        $this->result = $this->get->fetch();
        $this->checkExist = $this->get->rowCount();

        if ($this->checkExist == 1) {
            $this->id = $this->result['id'];
            $this->server = $this->result['name'];
            $this->namePVE = $this->result['name_api'];
            $this->countryPVE = $this->result['country'];
            $this->networkPVE = $this->result['network'];
            $this->cityPVE = $this->result['city'];
            $this->datacenterPVE = $this->result['datacenter'];
            $this->adress_ipPVE = $this->result['adress_ip'];
            $this->key_apiPVE = $this->result['key_api'];
            $this->pass_apiPVE = $this->result['pass_api'];
        } else {
            echo 'Erreur de synchronisation';
            exit();
        }
    }

    public function reinstallVMTable(string $id_service, int $vm_id, int $userid, string $image){
        $this->update = $this->pdo->prepare('UPDATE `cloud_servers` SET status = :status, image = :image WHERE idservice = :idservice');
        $this->update->bindValue(':status', 'pending', $this->pdo::PARAM_STR);
        $this->update->bindValue(':image', $image, $this->pdo::PARAM_STR);
        $this->update->bindValue(':idservice', $id_service, $this->pdo::PARAM_STR);
        $this->update->execute();
    }

    public function updateInstall(string $id_service, int $vm_id, int $userid, string $image, string $address_ip, string $password){
        $this->update = $this->pdo->prepare('UPDATE `cloud_servers` SET status = :status, vm_id = :vm_id, address_ip = :address_ip, password = :password WHERE idservice = :idservice');
        $this->update->bindValue(':status', 'created', $this->pdo::PARAM_STR);
        $this->update->bindValue(':vm_id', $vm_id, $this->pdo::PARAM_STR);
        $this->update->bindValue(':address_ip', $address_ip, $this->pdo::PARAM_STR);
        $this->update->bindValue(':password', $password, $this->pdo::PARAM_STR);
        $this->update->bindValue(':idservice', $id_service, $this->pdo::PARAM_STR);
        $this->update->execute();
    }
}
