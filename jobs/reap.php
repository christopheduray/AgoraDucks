<?php
require __DIR__.'/../autoload.php';
DotEnv::load();


foreach(Txn::getExpiredIds() as $id){
    $T=Txn::load($id);

    $P=new Payconiq($_ENV['PAYCONIQ_API_KEY'],$_ENV['ENVTYPE']=='production');

    $det=$P->getPaymentDetail($T->payment_id);
    $T->payment_trace=json_encode($det);
    $T->save();

    if($det && $det->status=='SUCCEEDED'){
        $T->processAsPaid();
    } else {
        $T->statut=2;
        $T->save();
    
        echo "Id: $id - ".$T->payment_id."\n";
        
        $q=DB::get()->prepare("update duck set id_txn=0, statut=0 where id_txn=:id");
        $q->bindValue('id',$id);
        $q->execute();
    }
    
}