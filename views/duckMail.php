<div style='width: 600px; margin: 20px; border: 1px solid #ddd; border-radius: 10px; break-inside: avoid;'>
    <div style='padding: 20px;'>
    <h2 style='font-size: 1.2em; text-align: center;'>Certificat d'authenticité de ton canard</h2>
    <table>
        <tr><td>
            <img src="<?=$qrCode?>" alt="QR Code"  style='width: 90%;' />
            <div style=''>Code de sécurité: <?= $token ?></div>
        </td><td>
            <img src="<?=$logo?>" style='width: 80%;' />
            <div style='font-family: monospace; font-size: 3em; text-align: center;'><?= sprintf("%04d",$id) ?></div>
        </td></tr>
    </table>
    <div style='text-align: center;'><?= $this->email ?> / <?= $this->gsm ?></div>
    </div>
</div>