<?php
use chillerlan\QRCode\{QRCode, QROptions};

class Duck extends Model {
    protected static $baseTable='duck';
    protected $attrs=[
        'id'=>0,
        'statut'=>0,
        'expiration'=>null,
        'ts_vente'=>null,
        'email'=>null,
        'gsm'=>null,
        'surnom'=>null,
        'id_txn'=>0,
        'token'=>''
    ];

    public function __construct(){
        $tk=new Token();
        $this->token=$tk->get();
    }

    public static function randomDuckIds(int $number){
        if($number<=0) return [];
        $q=DB::get()->prepare("select id from duck where statut=0 order by rand() limit $number");
        $q->execute();
        $ar=[];
        while($r=$q->fetch(PDO::FETCH_ASSOC)) $ar[]=$r['id'];
        return $ar;
    }

    public static function getAvailableIds(){
        $q=DB::get()->prepare("select id from duck where statut=0");
        $q->execute();
        $ar=[];
        while($r=$q->fetch(PDO::FETCH_ASSOC)){
            $ar[]=$r['id'];
        }
        return $ar;
    }

    public static function isAvailable($id){
        $D=Duck::load($id);
        return $D->statut==0;
    }

    public static function fromTxn($id){
        $ar=[];
        $q=DB::get()->prepare("select * from duck where id_txn=:id");
        $q->bindValue('id',$id);
        $q->execute();
        while($r=$q->fetch(PDO::FETCH_ASSOC)){
            $D=new Duck();
            $D->hydrate($r);
            $ar[]=$D;
        }
        return $ar;
    }

    public function renderHTML(){
        ob_start();
        $id=$this->id;
        $token=$this->token;
        $gsm=$this->gsm;
        $email=$this->email;

        $qrCode=(new QRCode)->render($_ENV['PUBLIC_URL']."validate.php?id=$id&token=$token");

        include __DIR__.'/../views/duckSub.php';

        return ob_get_clean();

    }

}