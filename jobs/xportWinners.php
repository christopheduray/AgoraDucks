<?php
require __DIR__.'/../autoload.php';
use Fwk\DB;
use Shuchkin\SimpleXLSXGen;

DotEnv::load();


$q=DB::get()->prepare("select 
            s.id as position, d.id, d.email, d.gsm, 
            l.cadeau, l.sponsor
        from duck d join scan s on d.id=s.id_duck
            join lots l on s.id=l.id
    where s.id <=190
    order by s.id");
$q->execute();

$data=[];
$data[]=['Position','N° Canard','Email','Tel','Cadeau','Sponsor'];
while($r=$q->fetch(PDO::FETCH_NUM)) $data[]=$r;

SimpleXLSXGen::fromArray($data)->saveAs(__DIR__.'/../storage/winningDucks.xlsx');