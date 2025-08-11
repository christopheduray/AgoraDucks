<?php
use Fwk\Router;

class PublicPagesCtrl {

    public static function mainPage(){
        View::render('mainForm');
    }

    public static function duckValidator(){
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
    }

    public static function pc_callback(){               
        $payload = json_decode(file_get_contents("php://input"));

        $fh=fopen(__DIR__.'/../storage/'.date('YmdHis').'log','w');
        fwrite($fh,$payload);
        fclose($fh);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "JSON decoding error: " . json_last_error_msg();
            die();
        }

        $T=Txn::load($payload->paymentId);

        if($T){
            $T->processAsPaid(); // effectue également l'envoi mail
        }
    }

    public static function pay_check(){
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
                    $url=$det->_links->checkout->href;
                    if($_ENV['ENVTYPE']!='production') $url=preg_replace("/^https:\/\//","https://ext.",$url);
                    header('Location: '.$url);
                } else if($det && $det->status=='CANCELLED'){
                    $T->processAsCancelled();
                    $statut=2;
                } else if($det && $det->status=="AUTHORIZATION_FAILED"){
                    $T->processAsCancelled();
                    $statut=2;
                }
            }

            $ducks=Duck::fromTxn($T->id);
            View::render('payOk',['statut'=>$statut,'ducks'=>$ducks]);
        }
    }

    public static function genRoutes(){
        Router::add('GET','/pc_callback',[static::class,'pc_callback']); // retour de PayConiq
        Router::add('GET','/pay_check',[static::class,'pay_check']);
        Router::add('GET','/validate',[static::class,'duckValidator']);  // vérification QR
        Router::add('GET','',[static::class,'mainPage']);
    }

}