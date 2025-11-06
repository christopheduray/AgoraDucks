<?php
require __DIR__.'/../autoload.php';
use Fwk\DB;
DotEnv::load();

die();  // ok
$wrong=[
['email'=>'anne.mauroit@live.be','ducks'=>[106,291]],
['email'=>'annesophie.derrey@gmail.com','ducks'=>[941,1369,2112,1398]],
['email'=>'Aureliemathieu40@gmail.com ','ducks'=>[74,2228,971,53]],
['email'=>'cecile_lambotte@hotmail.fr','ducks'=>[1609,409,2249,1006,2007,1608]],
['email'=>'celine.vaneukem@hotmail.be','ducks'=>[725,2070,946,1659,164,568,850,1061]],
['email'=>'coline.pettiaux@gmail.com','ducks'=>[2409,1157,245,829,611]],
['email'=>'delphedumont@yahoo.fr','ducks'=>[50]],
['email'=>'Edith.robert@outlook.be','ducks'=>[923,1087,2045,1612,1979,315,2183,2018]],
['email'=>'guisgandcoralie7@gmail.com','ducks'=>[523]],
['email'=>'katyjean73@gmail.com','ducks'=>[2191,2445,2256]],
['email'=>'marienachtergael17@gmail.com','ducks'=>[727,2125]],
['email'=>'maureenslx@hotmail.com','ducks'=>[1208,2301,2306]],
['email'=>'Merald.donnet@hotmail.com','ducks'=>[222,79,1973,177,1018]],
['email'=>'opheliedecock254@gmail.com','ducks'=>[444,283]],
['email'=>'payel.mariannick@hotmail.fr','ducks'=>[710,2437,2100,331]],
['email'=>'severinedeschaep@yahoo.fr','ducks'=>[802,204,679]],
['email'=>'verostlouis66@hotmail.fr','ducks'=>[233,2489]],
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