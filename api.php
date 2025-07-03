<?php
require 'autoload.php';

DotEnv::load();

switch($_POST['action']??''){
    case 'isAvailable':
        echo json_encode([ 'available'=>Duck::isAvailable($_POST['duckNr']??0)]);
        break;
    case 'order':
        $ducks=$_POST['ducks']??[];
        $nb=$_POST['nb']??0;
        $email=$_POST['email']??'';
        $gsm=$_POST['gsm']??'';

        $T=new Txn();
        $T->gsm=$gsm;
        $T->email=$email;
        $T->payload=json_encode($_POST);
        $T->save();

        if($nb>0)
        $P=new Payconiq($_ENV['PAYCONIQ_API_KEY'],$_ENV['ENVTYPE']=='production');
        $payment=$P->requestPayment([
            'amount'=>Config::PRICE*$nb*100,
            'callbackUrl'=>$_ENV['PUBLIC_URL'].'pc_callback.php',
            'currency'=>'EUR',
            'description'=>"$email / $gsm / $nb",
            'returnUrl'=>$_ENV['PUBLIC_URL'].'pay_check.php?id_pay='.$T->id.'&token='.$T->token
        ]);

        $exp=new DateTime($payment->expiresAt);
        $exp->setTimezone(new DateTimeZone('Europe/Brussels'));
        $T->expiration_ts=$exp->format('Y-m-d H:i:s');
        $T->payment_id=$payment->paymentId;        
        $T->payment_trace=json_encode($payment);
        $T->save();

        $err=0;
        $ducks=[...$ducks,...Duck::randomDuckIds($nb-count($ducks))];
        $pendingSave=[];
        foreach($ducks as $id){
            $D=Duck::load($id);
            if($D->statut!=0) $err++;
            else {
                $D->statut=1;
                $D->id_txn=$T->id;
            }
            $pendingSave[]=$D;
        }

        if(!$err){
            foreach($pendingSave as $D) $D->save();
            $url=$payment->_links->checkout->href;
            if($_ENV['ENVTYPE']!='production') $url=preg_replace("/^https:\/\//","https://ext.",$url);
            echo json_encode([ 'state'=>'ok','url'=>$url, 'ducks'=>$ducks]);
        } else {
            echo json_encode([ 'state'=>'nok' ]);
        }

        
        break;

}

