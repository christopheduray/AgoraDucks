<?php
require __DIR__.'/../autoload.php';
use Fwk\DB;
use Shuchkin\SimpleXLSXGen;

DotEnv::load();


$q=DB::get()->prepare("select id, email, gsm, id_txn, token from duck order by id");
$q->execute();
$data=[];
$data[]=["N° du canard","e-mail","GSM","Transaction","Token"];
while($r=$q->fetch(PDO::FETCH_NUM)) $data[]=$r;

SimpleXLSXGen::fromArray($data)->saveAs(__DIR__.'/../storage/allDucks.xlsx');