<?php
require 'autoload.php';
DotEnv::load();

$id=$_GET['id']??0;
$token=$_GET['token']??0;

if($id && $token){

    $D=Duck::load($id);
    if($D->token==$token){
        View::render("duckValidation",[ 'duck'=>$D ]);
        exit(0);
    } 
}

View::render("duckValidation",[ 'duck'=>null ]);