<?php
namespace src\Cloud\Database;

use src\Database\Database;

class CloudBillingTable
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
    }

    public function getAllLocatesTable()
    {
        $this->select = $this->pdo->prepare('SELECT * FROM `cloud_location` ORDER BY id ASC');
        $this->select->execute();
        $this->exist = $this->select->rowCount();
        while ($this->result = $this->select->fetch(\PDO::FETCH_ASSOC)) {

            $this->id = $this->result['id'];
            $this->country = $this->result['country'];
            $this->zone = $this->result['zone'];
            $this->flag = $this->result['flag'];
            $this->status = $this->result['status'];

            echo '<div class="col-4">
                    <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                            <img src="/templates/assets/img/'.$this->flag.'.png" width="150px" height="120px">
                            </div>
                            <div class="col-sm-4">
                            <br><h3>'.$this->country.'</h3><small>'.$this->zone.'</small>
                            </div>
                            <div class="col-sm-2">
                            <br>';

            if($this->status == "active"){
                echo '<a href="/cloud/deploy?locate='.$this->id.'" class="btn btn-primary"><i class="bi bi-check-circle-fill"></i></a>';
            } else {
                echo '<a href="" class="btn btn-dark" disabled=""><i class="bi bi-x-octagon-fill"></i></a>';
            }
            echo '</div>
                        </div>
                    </div>
                </div>
            </div>';

        }
    }

    public function getLocate(int $id){
        $this->get = $this->pdo->prepare('SELECT * FROM `cloud_location` WHERE id = :id');
        $this->get->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $this->get->execute();
        $this->result = $this->get->fetch();

        $this->id = $this->result['id'];
        $this->country = $this->result['country'];
        $this->zone = $this->result['zone'];
        $this->flag = $this->result['flag'];
        $this->status = $this->result['status'];
    }

    public function getImagesTable(string $country)
    {
        $this->get = $this->pdo->prepare('SELECT * FROM `cloud_images` WHERE id_locate = :id_locate ORDER BY id ASC');
        $this->get->bindValue(':id_locate', $country, $this->pdo::PARAM_STR);
        $this->get->execute();
        $this->exist = $this->get->rowCount();
        while ($this->result = $this->get->fetch(\PDO::FETCH_ASSOC)) {

            $this->id = $this->result['id'];
            $this->image = $this->result['image'];
            $this->id_clone = $this->result['id_clone'];
            $this->release = $this->result['date'];
            $this->status = $this->result['status'];

            echo '<div class="col-4">
                   <div class="card">
                   <div class="card-body">
                       <div class="row">
                           <div class="col-sm-4">
                           <br><img src="/templates/assets/img/' . $this->image . '.png" width="130px" height="100px">
                           </div>
                           <div class="col-sm-6">
                           <br><h3>' . $this->image . '</h3><small>Date de sortie : ' . $this->release . '</small>
                           </div>
                           <div class="col-sm-2">
                           <br>';

            if ($this->status == "active") {
                echo '<a href="/cloud/deploy?locate=' . $country . '&image=' . $this->id . '" class="btn btn-primary"><i class="bi bi-check-circle-fill"></i></a>';

            } else {
                echo '<a href="" class="btn btn-dark" disabled=""><i class="bi bi-x-octagon-fill"></i></a>';
            }
            echo '</div>
                </div>
              </div>
            </div>
        </div>';
        }
    }

    public function getImage(int $id){
        $this->get = $this->pdo->prepare('SELECT * FROM `cloud_images` WHERE id = :id');
        $this->get->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $this->get->execute();
        $this->exist = $this->get->rowCount();
        $this->result = $this->get->fetch();

        if($this->exist == 1) {

            $this->id = $this->result['id'];
            $this->image = $this->result['image'];
            $this->id_clone = $this->result['id_clone'];
            $this->release = $this->result['date'];
            $this->status = $this->result['status'];

        } else {
            header('location: /cloud/deploy');
        }
    }

    public function getOffersTable(string $country, string $image)
    {
        $this->get = $this->pdo->prepare('SELECT * FROM `cloud_offer` WHERE id_locate = :id_locate ORDER BY id ASC');
        $this->get->bindValue(':id_locate', $country, $this->pdo::PARAM_STR);
        $this->get->execute();
        $this->exist = $this->get->rowCount();

        while ($this->result = $this->get->fetch(\PDO::FETCH_ASSOC)) {

            $this->id = $this->result['id'];
            $this->plan_name = $this->result['plan_name'];
            $this->virt = $this->result['virt'];
            $this->stock = $this->result['stock'];
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

            echo '<tr>
                      <th scope="row"><span class="badge bg-secondary">'.$this->plan_name.'</span></th>
                      <td>'.$this->cores.' vCPU</td>
                      <td><i class="bi bi-cpu-fill"></i> '.$this->cpu_model.'</td>
                      <td><i class="bi bi-memory"></i> '.$this->ram.' Mo</td>
                      <td><i class="bi bi-device-hdd-fill"></i> '.$this->space.'Go '.$this->hdd_model.'</td>
                      <td><i class="bi bi-diagram-3-fill"></i> '.$this->network_speed.'GBP/s</td>
                      <td>'.number_format($this->price, 2, ',', ' ').'<i class="bi bi-currency-euro"></i>/mo</td>';

            if($this->stock <= 0) {
                echo '<td><button class="btn btn-danger"><i class="bi bi-dash-circle-fill"></i> Hors stock</button></td>';
            } else {
                echo '<td><a href="/cloud/deploy?locate=' . $country . '&image=' . $image . '&offer=' . $this->id . '" class="btn btn-primary"><i class="bi bi-lightning-charge-fill"></i> Déployer</a></td>';
            }
            echo '</tr>';
        }
    }

    public function getOfferTable(string $id){
        $this->get = $this->pdo->prepare('SELECT * FROM `cloud_offer` WHERE id = :id');
        $this->get->bindValue(':id', $id, $this->pdo::PARAM_STR);
        $this->get->execute();
        $this->exist = $this->get->rowCount();
        $this->result = $this->get->fetch();

        if($this->exist == 1) {

            $this->id = $this->result['id'];
            $this->plan_name = $this->result['plan_name'];
            $this->virt = $this->result['virt'];
            $this->server = $this->result['server'];
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

        } else {
            header('location: /cloud/deploy');
            exit();
        }
    }

    public function createService(int $userid, string $image, string $renew_price, string $price, int $offer, string $plan_name, string $space, string $ram, string $cores, string $hdd_model, string $id_service, string $server){

        $this->insert = $this->pdo->prepare('INSERT INTO `cloud` SET userid = :userid, idservice = :idservice, offer = :offer, status = :status, firstpaymentamount = :firstpaymentamount, price = :price, expiry = :expiry, name = :name, date_created = :date_created, date_updated = :date_updated');
        $this->insert->bindValue(':userid', $userid, $this->pdo::PARAM_INT);
        $this->insert->bindValue(':idservice', $id_service, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':offer', $offer, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':status', 'active', $this->pdo::PARAM_STR);
        $this->insert->bindValue(':firstpaymentamount', $price, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':price', $renew_price, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':expiry', date('Y-m-d', strtotime('+1 month')), $this->pdo::PARAM_STR);
        $this->insert->bindValue(':name', $id_service, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':date_created', date('d-m-Y H:i:s'), $this->pdo::PARAM_STR);
        $this->insert->bindValue(':date_updated', date('d-m-Y H:i:s'), $this->pdo::PARAM_STR);
        $this->insert->execute();

        $this->insert = $this->pdo->prepare('INSERT INTO `cloud_servers` SET idservice = :idservice, userid = :userid, server = :server, status = :status, image = :image, address_ip = :address_ip, plan_name = :plan_name, space = :space, ram = :ram, cores = :cores, hdd_model = :hdd_model, date_created = :date_created, date_updated = :date_updated');
        $this->insert->bindValue(':idservice', $id_service, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':userid', $userid, $this->pdo::PARAM_INT);

        $this->insert->bindValue(':server', $server, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':status', 'pending', $this->pdo::PARAM_STR);
        $this->insert->bindValue(':image', $image, $this->pdo::PARAM_STR);

        $this->insert->bindValue(':address_ip', 'null', $this->pdo::PARAM_STR);
        $this->insert->bindValue(':plan_name', $plan_name, $this->pdo::PARAM_STR);

        $this->insert->bindValue(':space', $space, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':ram', $ram, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':cores', $cores, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':hdd_model', $hdd_model, $this->pdo::PARAM_STR);

        $this->insert->bindValue(':date_created', date('d-m-Y H:i:s'), $this->pdo::PARAM_STR);
        $this->insert->bindValue(':date_updated', date('d-m-Y H:i:s'), $this->pdo::PARAM_STR);
        $this->insert->execute();

    }

    public function getPromotion(string $code){
        $this->get = $this->pdo->prepare('SELECT * FROM `billing_promotions` WHERE code = :code');
        $this->get->bindValue(':code', $code, $this->pdo::PARAM_STR);
        $this->get->execute();
        $this->existCode = $this->get->rowCount();
    }

}