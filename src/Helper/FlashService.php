<?php
namespace src\Helper;

class FlashService {

    public function setFlash($message, $color){
        $_SESSION['flash'] = [
            'message' => $message,
            'color' => $color
        ];
    }

    public static function flash(){
        if(isset($_SESSION['flash'])){
            echo '<div class="alert alert-'.$_SESSION['flash']['color'].'" role="alert">'.$_SESSION['flash']['message'].'</div>';
            unset($_SESSION['flash']);
        }
    }

}