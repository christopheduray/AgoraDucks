<?php
use Fwk\Model;

class Txn extends Model {
    protected static $baseTable='txn';
    protected $attrs=[
        'id'=>0,
        'ts'=>'',
        'email'=>'',
        'gsm'=>'',
        'payment_id'=>'',
        'expiration_ts'=>'',
        'payload'=>'',
        'payment_trace'=>'',
        'statut'=>0,
        'token'=>''
    ];

    public function __construct(){
        $dt=new DateTime();
        $this->ts=$dt->format('Y-m-d H:i:s');
        $dt->add(new DateInterval('PT1H'));
        $this->expiration_ts=$dt->format('Y-m-d H:i:s');
        $this->token=uniqid();
    }

    public static function getExpiredIds(){
        $q=DB::get()->prepare("select id from txn where statut=0 and expiration_ts<current_timestamp");
        $q->execute();
        $ar=[];
        while($r=$q->fetch(PDO::FETCH_ASSOC)) $ar[]=$r['id'];
        return $ar;
    }

    public function processAsPaid(){
        if($this->statut==0){
            $this->statut=1;
            $this->save();
            //paiement OK
            $ducks=Duck::fromTxn($this->id);
            foreach($ducks as $D){
                $D->statut=2;
                $D->gsm=$this->gsm;
                $D->email=$this->email;
                $D->save();
            }
            $this->sendDucks();
        }
    }

    public function processAsCancelled(){
        if($this->statut==0){
            $this->statut=2;
            $this->save();
            $ducks=Duck::fromTxn($this->id);
            foreach($ducks as $D) $D->free();
        }
    }

    public static function byPaymentId($pid){
        $o=new static;
        $q=DB::get()->prepare("select * from txn where payment_id=:pid");
        $q->bindValue('pid',$pid);
        $q->execute();
        if($r=$q->fetch(\PDO::FETCH_ASSOC)){
            foreach($r as $k=>$v) $o->{$k}=$v;
            return $o;
        }
        return null;
    }

    public function sendDucks(){
        $ducks=Duck::fromTxn($this->id);
        ob_start();
        $nb_ducks=count($ducks);
        include __DIR__.'/../views/mailIntro.php';
        $msg=ob_get_clean();
        
        foreach($ducks as $D){
            $msg.=$D->renderHTML();
        }
        Mailer::send($this->email,count($ducks)>1?"Voici les certificats de tes canards":"Voici le certificat de ton canard",$msg);
    }
    
}

