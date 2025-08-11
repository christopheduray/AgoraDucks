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
        View::render('adminMenu');
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

    public static function genRoutes(){
        Router::add('POST','/admin/ajax',[static::class,'ajax']);
        Router::add('GET','/admin/txn',[static::class,'txn']);
        Router::add('GET','/admin/ducks',[static::class,'ducks']);
        Router::add('GET','/admin/dump',[static::class,'dump']);
        Router::add('GET','/admin',[static::class,'menu']);

    }

}