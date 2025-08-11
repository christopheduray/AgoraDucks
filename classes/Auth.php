<?php

use Fwk\Router;

class Auth {

    public static function login(){
        if($_POST['action']=='login' && $_POST['password']==$_ENV['APP_PASSWORD']){
            $_SESSION['ADMIN']=1;
            Router::redirect('/');
        } else {
            sleep(3);
            static::display("Mot de passe erronné");
        }
    }

    public static function logout(){
        session_destroy();
        Router::redirect('/');
    }

    public static function display($errMsg=""){
        View::render('loginForm',[ 'errMsg'=>$errMsg ]);
    }

    public static function genRoutes(){
        Router::add('POST','/login',[static::class, 'login']);
        Router::add('GET','/logout',[static::class, 'logout']);
        Router::add('GET','/login',[static::class, 'display']);
    }

}