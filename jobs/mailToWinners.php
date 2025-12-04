<?php
require __DIR__.'/../autoload.php';
use Fwk\DB;
DotEnv::load();


$q=DB::get()->prepare("
    select d.email, d.id, s.id as position, 
            l.sponsor, l.valeur, l.cadeau
        from duck d join scan s on d.id=s.id_duck
            join lots l on s.id=l.id
    where s.id <=190
");

$q->execute();

$toMail=[];
while($r=$q->fetch(PDO::FETCH_ASSOC)){
    if(!array_key_exists($r['email'],$toMail))$toMail[$r['email']]=[];
    $toMail[$r['email']][]=$r;
}

foreach($toMail as $email=>$ducks){
    echo "$email - ".count($ducks)."\n";

    ob_start();
    include __DIR__.'/../views/mailWinner.php';
    $msg=ob_get_clean();

    Mailer::send($email,"Adopte ton canard - tu as gagné!",$msg);
    sleep(1);
}

