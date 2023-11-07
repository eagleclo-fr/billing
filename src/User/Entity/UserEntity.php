<?php
namespace src\User\Entity;

use src\Helper\IP;

class UserEntity {

    public function IDCustomer(){
        return $this->id_customer = 'ID-'.rand(100000000, 900000000).'';
    }

    public function getIP(){
        return $this->ip = IP::get();
    }

    public function getKeyUser(){
        return $this->key = uniqid();
    }

    public function getFirstname($firstname){
        $this->firstname = $firstname;
        return $this->firstname;
    }

    public function getLastname($lastname){
        $this->lastname = $lastname;
        return $this->lastname;
    }

    public function getMail($mail){
        $this->mail = $mail;
        return $this->mail;
    }

    public function getPassword($password){
        $this->password = $password;
        return $this->password;
    }

    public function getConfirmPassword($passwordConfirm){
        $this->passwordConfirm = $passwordConfirm;
        return $this->passwordConfirm;
    }

    public function getPasswordDecoded($passwordDecoded){
        $this->passwordDecoded = $passwordDecoded;
        return $this->passwordDecoded;
    }

    public function getAddress($address){
        $this->address = $address;
        return $this->address;
    }

    public function getRegion($region){
        $this->region = $region;
        return $this->region;
    }

    public function getCity($city){
        $this->city = $city;
        return $this->city;
    }

    public function getCountry($country){
        $this->country = $country;
        return $this->country;
    }


}