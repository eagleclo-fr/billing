<?php
namespace src\Router;

class RouterHelper
{

    public function redirect($param){
        header('location: '.$param.'');
    }

}