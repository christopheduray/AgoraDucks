<?php
use Fwk\Router;

class API {

    public static function isAvailable(){
        return [ 'available'=>Duck::isAvailable($_POST['duckNr']??0)];
    }

    public static function directSell(){
        
        $ducks=$_POST['ducks']??[];
        $nb=$_POST['nb']??0;
        $email=$_POST['email']??'';
        $gsm=$_POST['gsm']??'';

        $T=new Txn();
        $T->gsm=$gsm;
        $T->email=$email;
        $T->payload=json_encode($_POST);
        $T->statut=1;
        $T->save();
        $err=0;
        $ducks=[...$ducks,...Duck::randomDuckIds($nb-count($ducks))];
        if(count($ducks)<$nb) $err++;
        $pendingSave=[];
        foreach($ducks as $id){
            $D=Duck::load($id);
            if($D->statut!=0) $err++;
            else {
                $D->email=$email;
                $D->gsm=$gsm;
                $D->ts_vente=date('Y-m-d H:i:s');
                $D->statut=2;
                $D->id_txn=$T->id;
            }
            $pendingSave[]=$D;
        }

        if(!$err){
            foreach($pendingSave as $D) $D->save();
            $T->sendDucks();
            return [ 'state'=>'ok', 'url'=>'pay_check.php?id_pay='.$T->id.'&token='.$T->token, 'ducks'=>$ducks ];
        } else {
            return [ 'state'=>'nok' ];
        }
    }

    public static function order(){
        
        $ducks=$_POST['ducks']??[];
        $nb=$_POST['nb']??0;
        $email=$_POST['email']??'';
        $gsm=$_POST['gsm']??'';

        $T=new Txn();
        $T->gsm=$gsm;
        $T->email=$email;
        $T->payload=json_encode($_POST);
        $T->save();

        $err=0;
        if($nb>0){
            $P=new Payconiq($_ENV['PAYCONIQ_API_KEY'],$_ENV['ENVTYPE']=='production');
            $payment=$P->requestPayment([
                'amount'=>Config::PRICE*$nb*100,
                'callbackUrl'=>$_ENV['PUBLIC_URL'].'pc_callback/',
                'currency'=>'EUR',
                'description'=>"$email / $gsm / $nb",
                'returnUrl'=>$_ENV['PUBLIC_URL'].'pay_check/?id_pay='.$T->id.'&token='.$T->token
            ]);

            $exp=new DateTime($payment->expiresAt);
            $exp->setTimezone(new DateTimeZone('Europe/Brussels'));
            $T->expiration_ts=$exp->format('Y-m-d H:i:s');
            $T->payment_id=$payment->paymentId;        
            $T->payment_trace=json_encode($payment);
            $T->save();

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
            if(count($ducks)<$nb)$err++;
        }

        if(!$err){
            foreach($pendingSave as $D) $D->save();
            $url=$payment->_links->checkout->href;
            if($_ENV['ENVTYPE']!='production') $url=preg_replace("/^https:\/\//","https://ext.",$url);
            return [ 'state'=>'ok','url'=>$url, 'ducks'=>$ducks];
        } else {
            return [ 'state'=>'nok' ];
        }
    }

    public static function getRanking(){
        $q=Fwk\DB::get()->prepare("select * from scan order by id");
        $q->execute();
        echo json_encode($q->fetchAll(PDO::FETCH_ASSOC),1);
    }

    public static function handle(){
        $rJson=['err'=>1];
        switch($_POST['action']??''){
            case 'isAvailable':
                $rJson=static::isAvailable();
                break;

            case 'directSell':
                $rJson=static::directSell();
                break;

            case 'order':
                $rJson=static::order();
                break;

        }
        echo json_encode($rJson,1);
    }

    public static function genRoutes(){
        Router::add('POST','/api',[static::class, 'handle']);
        Router::add('GET','/api/getRanking',[static::class,'getRanking']);
    }

}