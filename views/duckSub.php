<div class='duckCard'>

    <h2>Certificat d'authenticité de ton canard</h2>
    <img src="images/logo_demicercle.png" class='bgPic' />
    <img src="<?= $qrCode ?>" alt="QR Code"  class='duckQR' />
    <div class='uniq'>Code de sécurité: <?= $token ?></div>

    <div class='numero'><?= sprintf("%04d",$id) ?></div>

    <div class='proprietaire'><?= $this->email ?> / <?= $this->gsm ?></div>

</div>