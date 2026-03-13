<?php
require __DIR__.'/../autoload.php';
use Fwk\DB;
DotEnv::load();

die();  // ok
$wrong=[
	['email'=>'someone@live.be','ducks'=>[106,291]],
];

foreach($wrong as $w){
    $q=DB::get()->prepare("select d.id from duck d join txn t on d.id_txn=t.id
        where t.statut=1 and t.email=:email");
    $q->bindValue('email',$w['email']);
    $q->execute();
    $ar=[];
    while($r=$q->fetch(PDO::FETCH_ASSOC)){
        $ar[]=$r['id'];
    }

    $notGood=array_diff($w['ducks'],$ar);

    
    ob_start();
    include __DIR__.'/../views/mailOopsie.php';
    $msg=ob_get_clean();
    
    Mailer::send($w['email'],"Il y a eu un couac... ",$msg);

    echo $w['email'].': NG('.join(',',$notGood).') - G('.join(',',$ar).")\n";

}
