<?php
require __DIR__.'/../autoload.php';
DotEnv::load();


foreach(Txn::getExpiredIds() as $id){
    $T=Txn::load($id);
    $T->statut=2;
    $T->save();

    echo "Id: $id - ".$T->payment_id."\n";
    
    $q=DB::get()->prepare("update duck set id_txn=0, statut=0 where id_txn=:id");
    $q->bindValue('id',$id);
    $q->execute();

}