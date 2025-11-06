<?php
use Fwk\Router;

class PublicPagesCtrl {

    public static function mainPage(){
        if($_ENV['SOLDOUT']??false)
            View::render('soldOut');
        else
            View::render('mainForm');
    }

    public static function duckValidator(){
        $id=$_GET['id']??0;
        $token=$_GET['token']??'';

        if($id){
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
        fwrite($fh,json_encode($payload));
        fclose($fh);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "JSON decoding error: " . json_last_error_msg();
            die();
        }

        $T=Txn::byPaymentId($payload->paymentId);

        if($T){
            $T->payment_trace=json_encode($payload);
            $T->save();
            if($payload->status=='SUCCEEDED')
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

    public static function scan(){
        $id=$_GET['id']??0;
        $token=$_GET['token']??'';

        if($id){
            $D=Duck::load($id);

            
            $err="";
            if($D->token==$token){
                $q=Fwk\DB::get()->prepare("select * from scan where id_duck=:id");
                $q->bindValue('id',$id);
                $q->execute();
                if($r=$q->fetch(PDO::FETCH_ASSOC)) $err="Ce canard a déjà été scanné (position: $r[id])";
                else {
                    $S=new Scan();
                    $S->hydrate([
                        'id_duck'=>$D->id
                    ]);
                    $S->save();    
                }
            }

            $q=Fwk\DB::get()->prepare("select s.id, s.id_duck, d.email, d.gsm from scan s join duck d on s.id_duck=d.id order by id");
            $q->execute();
            $SCANS=$q->fetchAll(PDO::FETCH_OBJ);
            View::render("duckScan",[ 'DUCK'=>$D, 'SCANS'=>$SCANS, 'err'=>$err ]);
        } else {
            echo "Cette page n'est pas supposée être appelée en direct";
        }

    }

    public static function genRoutes(){
        Router::add('POST','/pc_callback',[static::class,'pc_callback']); // retour de PayConiq
        Router::add('GET','/pay_check',[static::class,'pay_check']);
        Router::add('GET','/validate',[static::class,'duckValidator']);  // vérification QR
        Router::add('GET','/scan',[static::class,'scan']);  // ligne d'arrivée
        Router::add('GET','',[static::class,'mainPage']);

        
    }

}