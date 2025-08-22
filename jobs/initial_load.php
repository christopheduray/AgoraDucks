<?php
require __DIR__.'/../autoload.php';


DotEnv::load();

echo "This will add ".Config::MAXDUCKS." ducks to the system and empty the whole Txn table. Please confirm (yes)\n> ";
$answer=readline();
if($answer=="yes"){
    echo "Emptying tables...\n";
    $q=DB::get()->prepare("truncate table duck");
    $q->execute();
    $q=DB::get()->prepare("truncate table txn");
    $q->execute();
    echo "Loading ducks...\n";
    for($i=0;$i<Config::MAXDUCKS;$i++){
        $D=new Duck();
        $D->save();
        if($i && !($i%100)) echo $i."... ";
    }
    echo "\n";
}
