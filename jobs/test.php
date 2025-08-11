<?php
require __DIR__.'/../autoload.php';
DotEnv::load();


$T=Txn::load(17);
$T->sendDucks();