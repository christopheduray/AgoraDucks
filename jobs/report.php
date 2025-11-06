<?php
require __DIR__.'/../autoload.php';
use Fwk\DB;
DotEnv::load();



    ob_start();
    include __DIR__.'/../views/mailReport.php';
    $msg=ob_get_clean();
    
    $q=DB::get()->prepare("select distinct email from duck");
    $q->execute();
    while($r=$q->fetch(PDO::FETCH_ASSOC)){
        echo $r['email'];
        Mailer::send($r['email'],"Adopte ton canard - la course aura enfin lieu ce samedi",$msg);
        sleep(1);
    }


    

