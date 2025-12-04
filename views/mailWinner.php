<?php
    $pl=count($ducks)>1;
?>

<h1 style='color: 3c9fe8;'>Félicitations</h1>

<p><?= $pl?"Tes canards se sont bien battus et ont gagné!":"Ton canard s'est bien battu et a gagné!" ?>

<p>Ci-dessous une petite vue de la performance:
    <table>
        <tr><th>Numéro</th><th>Position</th><th>Lot</th><th>Sponsor</th></tr>
        <?php
            foreach($ducks as $d){
                echo "<tr>
                        <td>".sprintf("%04d",$d['id'])."</td>
                        <td>$d[position]</td>
                        <td>$d[cadeau]</td>
                        <td>$d[sponsor]</td>
                        </tr>\n";
            }
        ?>
    </table>


<p>Si tu n'as pas pu récupérer ton gain les jour J, les lots sont à venir enlever:
<ul><li> le mercredi 12/11 de 17 h à 19h30;
    <li> le vendredi 14/11 de 15h à 18h.
    <li> le samedi 15/11 de 9h à 12h
</ul>

<p>Adresse:<br />

CHAUSSEE DE MONS 266<br />
7800 Ath<br />

<p> L'équipe de l'Agora