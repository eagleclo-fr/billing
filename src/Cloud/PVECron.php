<?php
namespace src\Cloud;

use src\Cloud\PVECloud;
use src\Database\Database;

class PVECron
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->PVECloud = new PVECloud();
    }

    public function run()
    {
        $this->select = $this->pdo->prepare('SELECT * FROM `cloud_servers` WHERE status = :status LIMIT 1');
        $this->select->bindValue(':status', 'pending', $this->pdo::PARAM_STR);
        $this->select->execute();
        $this->exist = $this->select->rowCount();
        while ($this->result = $this->select->fetch(\PDO::FETCH_ASSOC)) {

            echo 'ID CLOUD TABLE : '.$this->result['id'].'<br>';

            $this->idservice = $this->result['idservice'];
            $this->vm_id = $this->result['vm_id'];
            $this->userid = $this->result['userid'];
            $this->image = $this->result['image'];
            $this->cores = $this->result['cores'];
            $this->ram = $this->result['ram'];
            $this->space = $this->result['space'];
            $this->address_ip = $this->result['address_ip'];
            $this->server = $this->result['server'];

            $this->PVECloud->createVM($this->idservice, $this->vm_id, $this->userid, $this->image, $this->cores, $this->ram, $this->space, $this->address_ip, $this->server);

        }
    }

}