<?php
namespace src\Cloud;

use src\Database\Database;
use src\Cloud\Database\CloudBillingTable;
use src\Billing\Invoices;
use src\Router\RouterHelper;
use src\Helper\FlashService;
use src\User\User;

class CloudBilling
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->cloudBillingTable = new CloudBillingTable();
        $this->invoices = new Invoices();
        $this->router = new RouterHelper();
        $this->PVECloud = new PVECloud();
        $this->flash = new FlashService();
        $this->user = new User();
    }

    public function getAllLocates()
    {
        return $this->cloudBillingTable->getAllLocatesTable();
    }

    public function getImages(string $country)
    {
        return $this->cloudBillingTable->getImagesTable($country);
    }

    public function getOffers(string $country, string $image)
    {
        return $this->cloudBillingTable->getOffersTable($country, $image);
    }

    public function getOffer(string $id_offer)
    {
        $this->cloudBillingTable->getOfferTable($id_offer);

        $this->plan_name = $this->cloudBillingTable->plan_name;
        $this->space = $this->cloudBillingTable->space;
        $this->ram = $this->cloudBillingTable->ram;
        $this->cpu = $this->cloudBillingTable->cpu;
        $this->cores = $this->cloudBillingTable->cores;
        $this->network_speed = $this->cloudBillingTable->network_speed;
        $this->cpu_model = $this->cloudBillingTable->cpu_model;
        $this->hdd_model = $this->cloudBillingTable->hdd_model;
        $this->price = $this->cloudBillingTable->price;
    }

    public function deployCloud(int $userid, string $country, string $image, string $offer, string $price){

        $this->user->userInfo($userid);
        $this->cloudBillingTable->getOfferTable($offer);
        $this->plan_name = $this->cloudBillingTable->plan_name;
        $this->price = $this->cloudBillingTable->price;
        $this->renew_price = $this->cloudBillingTable->renew_price;
        $this->server = $this->cloudBillingTable->server;
        $this->space = $this->cloudBillingTable->space;
        $this->ram = $this->cloudBillingTable->ram;
        $this->cpu = $this->cloudBillingTable->cpu;
        $this->cores = $this->cloudBillingTable->cores;
        $this->hdd_model = $this->cloudBillingTable->hdd_model;

        if($this->user->solde >= $price){
            $this->afterSolde = ($this->user->solde - $price);

            $this->user->updateUserSolde($userid, $this->afterSolde);
            $this->id_service = 'instance-'.$userid.'-'.date('d-m-Y H:i:s');
            $this->date_paid = date('d-m-Y H:i:s');
            $this->product = 'Achat Cloud ('.$this->id_service.')';
            $this->invoices->createInvoiceCloud($userid, $price, $this->product, $this->date_paid);
            $this->cloudBillingTable->createService($userid, $image, $this->renew_price, $price, $offer, $this->plan_name, $this->space, $this->ram, $this->cores, $this->hdd_model, $this->id_service, $this->server);

            header('location: /cloud');

        } else {

            $this->flash->setFlash('<i class="bi bi-x-octagon-fill"></i> Vous n\'avez pas assez de solde sur votre compte.', 'danger');
        }

    }

    public function getPrice(string $price, string $code){
        $this->cloudBillingTable->getPromotion($code);
        if($this->cloudBillingTable->existCode == 1){
            $this->result = $this->cloudBillingTable->get->fetch();
            $this->getValue = $this->result['value'];
            $this->TotalPrice = number_format($price * 1 - ($price * 1 * ($this->getValue/100)), 2, '.', ' ');
        } else {
            header('location: /cloud/deploy');
        }
    }

    public function verifyPromo(string $code, int $id_package, string $image, string $locate){
        $this->cloudBillingTable->getPromotion($code);
        if($this->cloudBillingTable->existCode == 1){
            $this->result = $this->cloudBillingTable->get->fetch();
            if ((date('Y-m-d H:i:s') >= $this->result['startdate']) && (date('Y-m-d H:i:s') <= $this->result['expirationdate'])){
                if($this->result['uses'] < $this->result['maxuses']){
                    header('location: /cloud/deploy?locate='.$locate.'&image='.$image.'&offer='.$id_package.'&promo='.$code.'');
                } else {
                    $this->flash->setFlash('<i class="bi bi-x-octagon-fill"></i> Le code promotionnel est déjà au maximum de son utilisation. Veuillez réessayer. <br>Si il s\'agit d\'une erreur, vous pouvez nous contacter par email à contact@centercloud.fr.', 'danger');
                }
            } else {
                $this->flash->setFlash('<i class="bi bi-x-octagon-fill"></i> Code promotionnel expiré. Veuillez réessayer. <br>Si il s\'agit d\'une erreur, vous pouvez nous contacter par email à contact@centercloud.fr.', 'danger');
            }
        } else {
            $this->flash->setFlash('<i class="bi bi-x-octagon-fill"></i> Le code promotionnel entré est inexistant. Veuillez réessayer. <br>Si il s\'agit d\'une erreur, vous pouvez nous contacter par email à contact@centercloud.fr.', 'danger');
        }
    }

    public function retryPromo(int $id_package, string $image, string $locate){
        header('location: /cloud/deploy?locate='.$locate.'&image='.$image.'&offer='.$id_package.'');
    }

}