<?php
namespace src\Billing\Database;

use src\Database\Database;

class OfferTable
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
    }

    public function getOfferTable(int $id)
    {
        $this->get = $this->pdo->prepare('SELECT * FROM `cloud_offer` WHERE id = :id');
        $this->get->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $this->get->execute();
        $this->result = $this->get->fetch();
        $this->checkExist = $this->get->rowCount();

        if ($this->checkExist == 1) {
            $this->plan_name = $this->result['plan_name'];
            $this->virt = $this->result['virt'];
            $this->space = $this->result['space'];
            $this->ram = $this->result['ram'];
            $this->burst = $this->result['burst'];
            $this->swap = $this->result['swap'];
            $this->cpu = $this->result['cpu'];
            $this->cores = $this->result['cores'];
            $this->bandwidth = $this->result['bandwidth'];
            $this->network_speed = $this->result['network_speed'];
            $this->cpu_model = $this->result['cpu_model'];
            $this->hdd_model = $this->result['hdd_model'];
            $this->price = $this->result['price'];
            $this->renew_price = $this->result['renew_price'];
            $this->module = $this->result['module'];
        }
    }
}
