<?php
use Fwk\Model;
use Fwk\DB;
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

    // libérer le canard
    public static function free(){
        $this->statut=0;
        $this->id_txn=null;
        $this->email=null;
        $this->gsm=null;
        $this->surnom=null;
        $this->token='';
        $this->save();
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
        QRGen::gen($id,$token);
        $gsm=$this->gsm;
        $email=$this->email;
        $logo=$_ENV['PUBLIC_URL']."images/logo_demicercle.png";
        $qrCode=$_ENV['PUBLIC_URL']."storage/".QRGen::getFilename($id,$token);

        include __DIR__.'/../views/duckMail.php';
        return ob_get_clean();
    }

}