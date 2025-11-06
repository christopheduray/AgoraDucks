<?php
require __DIR__.'/../autoload.php';
use Fwk\DB;
use Dompdf\Dompdf;
use Dompdf\Options;

ini_set('memory_limit', '512M');

DotEnv::load();

$files=[];
$q=DB::get()->prepare("select * from duck order by id");
$q->execute();
while($r=$q->fetch(PDO::FETCH_ASSOC)){
    QRGen::genShort($r['id'],$r['token']);
    $files[$r['id']]=QRGen::getFilename($r['id'],$r['token']);
}

ob_start();
include __DIR__.'/../views/stickers.php';
$content=ob_get_clean();

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($content);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$output=$dompdf->output();
file_put_contents(__DIR__."/../storage/stickers.pdf",$output);

foreach($files as $f){
    unlink(__DIR__.'/../storage/'.$f);
}
