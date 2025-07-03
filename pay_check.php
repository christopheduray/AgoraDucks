<?php
require 'autoload.php';
DotEnv::load();

$statut=0;
if(($id_pay=$_GET['id_pay']??false) && ($token=$_GET['token']??false) ){
    $T=Txn::load((int)$_GET['id_pay']);
    if($T->token!=$token) die();

    $statut=$T->statut;
    if($statut==0){
        
        $P=new Payconiq($_ENV['PAYCONIQ_API_KEY'],$_ENV['ENVTYPE']=='production');

        $det=$P->getPaymentDetail($T->payment_id);
        $T->payment_trace=json_encode($det);
        $T->save();

        if($det && $det->status=='SUCCEEDED'){
            $T->processAsPaid();
        } else if($det && $det->status=='PENDING'){
            $url=$payment->_links->checkout->href;
            if($_ENV['ENVTYPE']!='production') $url=preg_replace("/^https:\/\//","https://ext.",$url);
            header('Location: '.$url);
        }
    }

    $ducks=Duck::fromTxn($T->id);
    View::render('payOk',['statut'=>$statut,'ducks'=>$ducks]);
}

