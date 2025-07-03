<?php

if($duck){

    $T=Txn::load($duck->id_txn);
    echo "<h1>Interface de vérification de canard</h1>\n";

    echo "<p>Félicitations!!! Votre canard ".sprintf($duck->id)." acheté le ".$T->ts." est bien authentique\n";

    echo $duck->renderHTML();

} else {

    echo "<h1>Interface de vérification de canard</h1>\n";

    echo "<p>Nous n'avons malheureusement pas pu vérifier l'authenticité de ce canard\n";
}