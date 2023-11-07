<?php
namespace src\Cloud;

use src\Database\Database;

class PVEIPs
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
    }


    public function getIPs(string $id_service, string $address_ip)
    {
        if($address_ip == 'null'){

            $this->select = $this->pdo->prepare('SELECT * FROM `cloud_ips` WHERE idservice = :idservice ORDER BY id ASC LIMIT 1');
            $this->select->bindValue(':idservice', 'null', $this->pdo::PARAM_STR);
            $this->select->execute();
            $this->exist = $this->select->rowCount();
            while ($this->result = $this->select->fetch(\PDO::FETCH_ASSOC)) {

                $this->id_ip = $this->result['id'];
                $this->address_ip = $this->result['address_ip'];
                $this->subnet_mask = $this->result['subnet_mask'];
                $this->mac = $this->result['mac'];
                $this->gateway = $this->result['gateway'];
                $this->dns = $this->result['dns'];
                $this->reverse_dns = $this->result['reverse_dns'];
                $this->vlan_tag = $this->result['vlan_tag'];
                $this->updateIp($this->id_ip, $id_service);

            }

            if ($this->exist <= 0) {
                $this->address_ip = '192.168.1.1';
                $this->subnet_mask = '24';
                $this->mac = '02:00:00:1a:7a:90';
                $this->gateway = '192.168.1.254';
                $this->dns = '1.1.1.1';
                $this->reverse_dns = 'ip-192.168.1.1.centercloud.fr';
                $this->vlan_tag = '10';
        }

        } else {
            $this->get = $this->pdo->prepare('SELECT * FROM `cloud_ips` WHERE address_ip = :address_ip');
            $this->get->bindValue(':address_ip', $address_ip, $this->pdo::PARAM_STR);
            $this->get->execute();
            $this->result = $this->get->fetch();
            $this->checkExist = $this->get->rowCount();

            if($this->checkExist == 1) {

                $this->id_ip = $this->result['id'];
                $this->address_ip = $this->result['address_ip'];
                $this->subnet_mask = $this->result['subnet_mask'];
                $this->mac = $this->result['mac'];
                $this->gateway = $this->result['gateway'];
                $this->dns = $this->result['dns'];
                $this->reverse_dns = $this->result['reverse_dns'];
                $this->vlan_tag = $this->result['vlan_tag'];

                $this->updateIp($this->id_ip, $id_service);

            } else {

                $this->address_ip = '192.168.1.1';
                $this->subnet_mask = '24';
                $this->mac = '02:00:00:1a:7a:90';
                $this->gateway = '192.168.1.254';
                $this->dns = '1.1.1.1';
                $this->reverse_dns = 'ip-192.168.1.1.centercloud.fr';
                $this->vlan_tag = '10';

            }
        }

    }

    public function updateIp(int $id_ip, string $id_service){
        $this->update = $this->pdo->prepare('UPDATE cloud_ips SET idservice = :idservice WHERE id = :id');
        $this->update->bindValue(':idservice', $id_service, $this->pdo::PARAM_STR);
        $this->update->bindValue(':id', $id_ip, $this->pdo::PARAM_STR);
        $this->update->execute();

    }

}