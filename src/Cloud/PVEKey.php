<?php
namespace src\Cloud;

use src\Database\Database;

class PVEKey
{

    public function __construct()
    {
        $this->database = New Database();
        $this->pdo = $this->database->connect();
    }


    public function credentialConnect(string $id_server)
    {
        if($id_server != null) {
            $this->get = $this->pdo->prepare('SELECT * FROM `cloud_pve` WHERE id = :id');
            $this->get->bindValue(':id', $id_server, $this->pdo::PARAM_STR);
            $this->get->execute();
            $this->result = $this->get->fetch();

                $this->name = $this->result['name'];
                $this->name_api = $this->result['name_api'];
                $this->HostProxmox = $this->result['adress_ip'];
                $this->KeyProxmox = $this->result['key_api'];
                $this->PassProxmox = $this->result['pass_api'];
                $this->RealmProxmox = $this->result['realm_api'];
                $this->PortProxmox = $this->result['port_api'];
        } else {
            $this->getMessage(402);
        }
    }

    private function getMessage(int $status)
    {
        switch ($status) {
            case 401:
                echo "[PVE] ERROR : the id of the selected server is not valid.";
                exit();
            case 402:
                echo "[PVE] ERROR : Not found the server in info on proxmox.";
                exit();
            default:
                echo "[PVE] ERROR : Error critique.";
                break;
        }
    }
}