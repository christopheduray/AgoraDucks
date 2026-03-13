<?php
require __DIR__.'/../autoload.php';

DotEnv::load();

foreach([89,90,91,93,95,97,98,100,107,114,118,133,134,136,137,138,139,144,146,161,162,163,178,192,193] as $id){
    $T=Txn::load($id);
    if($T){
        $T->statut=2;
        $T->save();
        $ducks=Duck::fromTxn($T->id);
        foreach($ducks as $D) $D->free();
    }
}

