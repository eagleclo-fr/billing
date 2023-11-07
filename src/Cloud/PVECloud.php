<?php
namespace src\Cloud;

use src\Database\Database;
use src\Cloud\PVEKey;
use ProxmoxVE\Proxmox;
use src\Helper\FlashService;
use src\Cloud\Database\CloudTable;
use src\Helper\Password;
use src\Cloud\PVEIPs;
use src\User\User;
use src\Cloud\PVE2_API;

class PVECloud
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->flash = new FlashService();
        $this->cloudTable = new CloudTable();
        $this->passwordHelper = new Password();
        $this->PVEIPs = new PVEIPs();
        $this->user = new User();
    }

    public function RootVPS(){
        $this->newPassPassword = $this->passwordHelper->passgen1(15);
        return $this->newPassPassword;
    }

    public function ConnectProxmox($node)
    {
        $this->proxmoxkey = new PVEKey();
        $this->proxmoxkey->credentialConnect($node);
        $this->credentials = ['hostname' => $this->proxmoxkey->HostProxmox, 'username' => $this->proxmoxkey->KeyProxmox, 'password' => $this->proxmoxkey->PassProxmox, 'realm' => $this->proxmoxkey->RealmProxmox, 'port' => $this->proxmoxkey->PortProxmox,];
        $this->proxmox = new Proxmox($this->credentials);
    }

    public function getStatusVM(int $vm_id, int $server_id){
        $this->ConnectProxmox($server_id);
        $return = ($this->proxmox->get('/nodes/'.$this->proxmoxkey->name_api.'/qemu/'.$vm_id.'/status/current'));
        $obj = json_encode($return, true);
        $decoded = json_decode($obj);
        $this->statusVM = $decoded->data->status;
        $this->uptimeVM = gmdate("H:i:s", $decoded->data->uptime);
    }

    public function actionVM(string $option, int $vm_id, string $id_service, int $server_id){
        $this->ConnectProxmox($server_id);
        if($option == "start") {
            $return = $this->proxmox->create('/nodes/'.$this->proxmoxkey->name_api.'/qemu/' . $vm_id . '/status/start');
            $this->flash->setFlash('Votre serveur <strong>'.$id_service.'</strong> à démarrer avec succès !', 'success');
            echo '<meta http-equiv="refresh" content="2; URL=/cloud/servers/'.$id_service.'/overview">';
        } else if($option == "shutdown") {
            $return = $this->proxmox->create('/nodes/'.$this->proxmoxkey->name_api.'/qemu/' . $vm_id . '/status/shutdown');
            $this->flash->setFlash('Votre serveur <strong>'.$id_service.'</strong> est éteint de force avec succès !', 'success');
            echo '<meta http-equiv="refresh" content="2; URL=/cloud/servers/'.$id_service.'/power">';
        } else if($option == "stop") {
            $return = $this->proxmox->create('/nodes/'.$this->proxmoxkey->name_api.'/qemu/' . $vm_id . '/status/stop');
            $this->flash->setFlash('Votre serveur <strong>'.$id_service.'</strong> est éteint avec succès !', 'success');
            echo '<meta http-equiv="refresh" content="1; URL=/cloud/servers/'.$id_service.'/overview">';
        } else if($option == "create_snapshot"){
            $data = [
                'snapname' => ''.$id_service.''.date('dmYHi').'',
                'description' => 'api.v1.centercloud.fr-'.$id_service.'',
            ];
            $return = $this->proxmox->create('/nodes/'.$this->proxmoxkey->name_api.'/qemu/' . $vm_id . '/snapshot', $data);
            $this->flash->setFlash('Votre serveur <strong>'.$id_service.'</strong> est en train de faire une sauvegarde...', 'success');
            echo '<meta http-equiv="refresh" content="1; URL=/cloud/servers/'.$id_service.'/snapshots">';
        }
    }

   
    public function getAllSnapshotVM(string $id_service, int $vm_id, int $userid, int $server_id){
        $return = $this->proxmox->get('nodes/'.$this->proxmoxkey->name_api.'/qemu/'.$vm_id.'/snapshot');
        $returnJson = json_encode($return, true);
        $decoded = json_decode($returnJson);

        for ($i=0; $i<25; $i++) {
            if((isset($decoded->data[$i]->name))) {
                if($decoded->data[$i]->name != "current"){

                    if(isset($_POST['delete'.$decoded->data[$i]->name.''])){
                        $return = $this->proxmox->delete('nodes/'.$this->proxmoxkey->name_api.'/qemu/'.$vm_id.'/snapshot/'.$decoded->data[$i]->name.'');
                        $this->cloudTable->addTask($id_service, $userid, 'delete_snapshot', 'success');
                        $this->flash->setFlash('Votre snapshot du service <strong>'.$id_service.'</strong> est supprimer avec succès !', 'success');
                        echo '<meta http-equiv="refresh" content="0; URL=/cloud/servers/'.$id_service.'/snapshots">';
                    }

                    if(isset($_POST['rollback'.$decoded->data[$i]->name.''])){
                        $return = $this->proxmox->create('nodes/'.$this->proxmoxkey->name_api.'/qemu/'.$vm_id.'/snapshot/'.$decoded->data[$i]->name.'/rollback');
                        $this->cloudTable->addTask($id_service, $userid, 'rollback_snapshot', 'success');
                        $this->flash->setFlash('Votre snapshot du service <strong>'.$id_service.'</strong> est en cours de restauration avec succès !', 'success');
                        echo '<meta http-equiv="refresh" content="0; URL=/cloud/servers/'.$id_service.'/snapshots">';
                    }

                    echo '<tr>
                    <td>'.$decoded->data[$i]->name.'</td>
                    <td>'.$decoded->data[$i]->description.'</td>
                    <td>'.gmdate("d/m/Y H:i:s", $decoded->data[$i]->snaptime).'</td>
                    <td><button type="submit" name="rollback'.$decoded->data[$i]->name.'" class="btn btn-primary"><i class="bi bi-cloud-download-fill"></i></button> <button type="submit" name="delete'.$decoded->data[$i]->name.'" class="btn btn-danger"><i class="bi bi-eraser-fill"></i></button></td>
                    </td>
                    </tr>';
                } else {
                    //
                }
            } else {
                //
            }
        }
    }

    private function getMessage(int $status)
    {
        switch ($status) {
            case 500:
                echo "[PVE] ERROR : The snapshot quota is exceeded.";
                exit();
            default:
                echo "[PVE] ERROR : Error critique.";
                break;
        }
    }

    public function deleteVM(int $vm_id, int $server_id){
        $this->ConnectProxmox($server_id);
        $this->proxmox->create('/nodes/'.$this->proxmoxkey->name_api.'/qemu/' . $vm_id . '/status/stop'); sleep(2);
        $return = $this->proxmox->delete('/nodes/'.$this->proxmoxkey->name_api.'/qemu/' . $vm_id . '');
    }

    public function createVM(string $id_service, string $vm_id, int $userid, int $image, string $cores, string $ram, string $space, string $address_ip, int $server_id){
        $this->ConnectProxmox($server_id);
        $this->password = $this->RootVPS();

        $this->user->userInfo($userid);

        if($image == '100'){
            $this->template = '100';
        } else if($image == '1000'){
            $this->template = '1000';
        } else if($image == '2000'){
            $this->template = '2000';
        } else if($image == '2100'){
            $this->template = '2100';
        } else {
            $this->template = '100';
        }

        if($vm_id != 'null'){
            $this->deleteVM($vm_id, $server_id);
        }

        $this->PVEIPs->getIPs($id_service, $address_ip);

        $this->nextId($server_id);
        $info = [
            'newid' => $this->vmid,
            'full' => '1',
            'storage' => 'local-lvm',
            'description' => 'Client : '.$this->user->mail,
            'vmid' => $this->template
        ];
        $this->createInstance = $this->proxmox->create('/nodes/'.$this->proxmoxkey->name_api.'/qemu/'.$this->template.'/clone', $info);

        $update = [
            'vmid' => $this->vmid,
            'sockets' => '1',
            'cores' => $cores,
            'memory' => $ram,
            'ciuser' => 'root',
            'cipassword' => $this->password,
            'agent' => '1',
            'nameserver' => $this->PVEIPs->dns,
            'name' => $id_service,
            'ipconfig0' => 'ip='.$this->PVEIPs->address_ip.'/'.$this->PVEIPs->subnet_mask.',gw='.$this->PVEIPs->gateway.'',
            'net0' => 'virtio,bridge=vmbr0,firewall=0,macaddr='.$this->PVEIPs->mac.',tag='.$this->PVEIPs->vlan_tag.''
        ];

        $this->update = $this->proxmox->create('/nodes/'.$this->proxmoxkey->name_api.'/qemu/'.$this->vmid.'/config', $update);

        $resize = [
            'vmid' => $this->vmid,
            'disk' => 'scsi0',
            'size' => $space.'G'
        ];

        $this->update = $this->proxmox->set('/nodes/'.$this->proxmoxkey->name_api.'/qemu/'.$this->vmid.'/resize', $resize);
        $this->startInstance = $this->proxmox->create('/nodes/'.$this->proxmoxkey->name_api.'/qemu/' . $this->vmid . '/status/start');
        $this->cloudTable->updateInstall($id_service, $this->vmid, $userid, $this->template, $this->PVEIPs->address_ip, $this->password);
    }

    public function nextId($server_id){
        $this->ConnectProxmox($server_id);
        $optional['vmid'] = !empty($vmid) ? $vmid : null;
        $return = $this->proxmox->get('/cluster/nextid');
        $returnJson = json_encode($return, true);
        $decoded = json_decode($returnJson);
        $this->vmid = $decoded->data;
    }

}