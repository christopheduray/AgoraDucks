<?php
use Fwk\Router;
use Fwk\DB;

class AdminPagesCtrl {

    public static function mustBeAdmin(){
        if(!($_SESSION['ADMIN']??false)){
            Router::redirect('login/');
            exit(0);
        }
    }

    public static function menu(){
        static::mustBeAdmin();
        $q=DB::get()->prepare("select statut, count(*) cnt from duck group by statut order by 1");
        $q->execute();

        $STATS=[];
        $STATLUP=['Libre','En attente','Réservé'];
        while($r=$q->fetch(PDO::FETCH_ASSOC)){
            $STATS[]=['statut'=>$STATLUP[$r['statut']], 'cnt'=>$r['cnt']];
        }

        View::render('adminMenu',['STATS'=>$STATS]);
    }

    public static function txn(){
        static::mustBeAdmin();
        $q=DB::get()->prepare("select * from txn order by ts desc");
        $q->execute();
        $TXN=$q->fetchAll(\PDO::FETCH_ASSOC);
        View::render('adminTxn',['TXN'=>$TXN]);
    }

    public static function ducks(){
        static::mustBeAdmin();
        $q=DB::GET()->prepare("select d.id, d.statut, t.email, t.gsm, t.ts, t.expiration_ts
            from duck d left join txn t on d.id_txn=t.id order by d.id");
        $q->execute();
        $DUCKS=$q->fetchAll(\PDO::FETCH_ASSOC);
        View::render('adminDucks',['DUCKS'=>$DUCKS]);
    }

    public static function ajax(){
        static::mustBeAdmin();
        $action=$_POST['action']??'';
        switch($action){
            case 'sendMail':
                $T=Txn::load($_POST['id_txn']);
                if($T) $T->sendDucks();
                echo "[]";
                break;
        }
    }

    public static function dump(){
        static::mustBeAdmin();
        echo "<pre>";
        print_r(array_map(function($r){ return $r['pattern']; }, Router::$routes));
        echo "</pre>";
    }

    public static function ranking(){
        static::mustBeAdmin();
        $q=Fwk\DB::get()->prepare("select s.id, s.id_duck, d.email, d.gsm from scan s join duck d on s.id_duck=d.id order by id");
        $q->execute();
        $SCANS=$q->fetchAll(PDO::FETCH_OBJ);
        View::render("duckScan",[ 'DUCK'=>null, 'SCANS'=>$SCANS, 'err'=>[] ]);
    }

    public static function genRoutes(){
        Router::add('POST','/admin/ajax',[static::class,'ajax']);
        Router::add('GET','/admin/txn',[static::class,'txn']);
        Router::add('GET','/admin/ducks',[static::class,'ducks']);
        Router::add('GET','/admin/dump',[static::class,'dump']);
        Router::add('GET','/admin/ranking',[static::class,'ranking']);
        
        Router::add('GET','/admin',[static::class,'menu']);
    }

}