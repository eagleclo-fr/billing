<?php
namespace src\Cloud;

use src\Database\Database;
use src\Cloud\Database\CloudTable;
use src\Billing\Database\OfferTable;
use src\Router\RouterHelper;
use src\Cloud\PVECloud;
use src\Helper\FlashService;

class Cloud
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->cloudTable = new CloudTable();
        $this->router = new RouterHelper();
        $this->offerTable = new OfferTable();
        $this->PVECloud = new PVECloud();
        $this->flash = new FlashService();
    }

    public function getAllCloud(int $userid)
    {
        return $this->cloudTable->getAllCloudTable($userid);
    }

    public function getCloud(int $userid, string $id_service){
        $this->cloudTable->getCloudTable($id_service);
        $this->getCloudServer($userid, $id_service);
        $this->getPVE($this->serverVM);
        if($this->statusVM == "created") {
            $this->PVECloud->getStatusVM($this->cloudTable->vm_id, $this->serverVM);
            $this->statusVM = $this->PVECloud->statusVM;
            $this->uptimeVM = $this->PVECloud->uptimeVM;
            $this->getCurrentState = 'created';
        } else if($this->statusVM == "pending") {
            $this->getCurrentState = 'pending';
        } else {
            $this->getCurrentState = 'pending';
        }

        $this->userid = $this->cloudTable->userid;
        $this->idservice = $this->cloudTable->idservice;
        $this->offer = $this->cloudTable->offer;
        $this->status = $this->cloudTable->status;
        $this->getStatus = $this->cloudTable->getStatus;
        $this->firstpaymentamount = $this->cloudTable->firstpaymentamount;
        $this->price = $this->cloudTable->price;
        $this->expiry = $this->cloudTable->expiry;
        $this->name = $this->cloudTable->name;
        $this->date_created = $this->cloudTable->date_created;
        $this->date_updated = $this->cloudTable->date_updated;

        if ($this->statusVM == "running") {
            $this->getStatusVM = '<button type="button" class="btn btn-success"><i class="bi bi-check-circle"></i></button>';
            $this->getStatusVMWriter = 'En ligne';
        } else if ($this->statusVM == "stopped") {
            $this->getStatusVM = '<button type="button" class="btn btn-danger"><i class="bi bi-x-octagon-fill"></i></button>';
            $this->getStatusVMWriter = 'Hors ligne';
        } else {
            $this->getStatusVM = '<button type="button" class="btn btn-dark"><i class="bi bi-bar-chart-fill"></i></button>';
            $this->getStatusVMWriter = 'Error';
        }

    }

    public function getCloudServer(int $userid, string $id_service){
        $this->cloudTable->getCloudServerTable($id_service);

        if($userid == $this->cloudTable->userid){

            $this->id_service = $this->cloudTable->id_service;
            $this->statusVM = $this->cloudTable->statusVM;
            $this->keyrootVM = $this->cloudTable->keyrootVM;
            $this->serverVM = $this->cloudTable->serverVM;
            $this->vm_id = $this->cloudTable->vm_id;
            $this->plan_nameVM = $this->cloudTable->plan_nameVM;
            $this->spaceVM = $this->cloudTable->spaceVM;
            $this->ramVM = $this->cloudTable->ramVM;
            $this->coresVM = $this->cloudTable->coresVM;
            $this->hdd_modelVM = $this->cloudTable->hdd_modelVM;

        } else {
            $this->router->redirect('/cloud');
        }
    }

    public function getOffer(int $id){
        $this->$this->offerTable($id);
        $this->plan_name = $this->offerTable->plan_name;
        $this->virt = $this->offerTable->virt;
        $this->space = $this->offerTable->space;
        $this->ram = $this->offerTable->ram;
        $this->burst = $this->offerTable->burst;
        $this->swap = $this->offerTable->swap;
        $this->cpu = $this->offerTable->cpu;
        $this->cores = $this->offerTable->cores;
        $this->bandwidth = $this->offerTable->bandwidth;
        $this->network_speed = $this->offerTable->network_speed;
        $this->cpu_model = $this->offerTable->cpu_model;
        $this->hdd_model = $this->offerTable->hdd_model;
        $this->price = $this->offerTable->price;
        $this->renew_price = $this->offerTable->renew_price;
        $this->module = $this->offerTable->module;
    }

    public function getTasks(string $id_service){
        return $this->cloudTable->getAllTasksTable($id_service);
    }

    public function getPVE(int $id){
        $this->cloudTable->getPVETable($id);

        $this->server = $this->cloudTable->server;
        $this->serverVM = $this->cloudTable->serverVM;
        $this->address_ipVM = $this->cloudTable->address_ipVM;
        $this->namePVE = $this->cloudTable->namePVE;
        $this->countryPVE = $this->cloudTable->countryPVE;
        $this->networkPVE = $this->cloudTable->networkPVE;
        $this->cityPVE = $this->cloudTable->cityPVE;
        $this->datacenterPVE = $this->cloudTable->datacenterPVE;
        $this->adress_ipPVE = $this->cloudTable->adress_ipPVE;
        $this->key_apiPVE = $this->cloudTable->key_apiPVE;
        $this->pass_apiPVE = $this->cloudTable->pass_apiPVE;
    }

    public function action(string $option, int $vm_id, string $id_service, int $userid, int $server_id){
        $this->PVECloud->actionVM($option, $vm_id, $id_service, $server_id);
        $this->cloudTable->addTask($id_service, $userid, $option, 'success');
    }

    public function getSnapshots(string $id_service, int $vm_id, int $userid, int $server_id){
        $this->PVECloud->getAllSnapshotVM($id_service, $vm_id, $userid, $server_id);
    }

    public function reinstallVM(string $id_service, string $vm_id, string $userid, string $image){
        if(!empty($image)){
            $this->cloudTable->reinstallVMTable($id_service, $vm_id, $userid, $image);
            $this->cloudTable->addTask($id_service, $userid, 'reinstall', 'success');
            $this->flash->setFlash('Votre serveur <strong>' . $id_service . '</strong> va débuter la réinstallation, veuillez patienter quelques secondes !', 'success');
            echo '<meta http-equiv="refresh" content="2; URL=/cloud/servers/' . $id_service . '/overview">';
        } else {
            $this->flash->setFlash('Il faut sélectionner une image !', 'danger');
        }
    }

}
