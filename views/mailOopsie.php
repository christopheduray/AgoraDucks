<h1 style='color: 3c9fe8;'>Oups</h1>

<p>Il y a eu un couac (pour des canards c'est normal me diras-tu).

<p>Suite à un souci de communication avec PayConiq, certaines transactions ont été marquées Ok par erreur,
     tu as donc reçu <?=(count($notGood)>1)?'les certificats suivants':'le certificat suivant' ?> alors que le paiement n'était pas passé:

<ul>
    <?php
        foreach($notGood as $ng)
            echo "<li>".sprintf("%04d",$ng)."</li>\n";
    ?>
</ul>

<p><?=(count($notGood)>1)?"Ces certificats ont été":"Ce certificat a été" ?> remis en circulation 
 (aucun canard n'a été blessé durant cette opération, rassure-toi)

<?php
if(count($ar)){
    echo "<p>Par contre ".(count($ar)>1?"les canards suivants te sont bien attribués:":"le canard suivant t'est bien attribué:");
    echo "<ul>";
    foreach($ar as $a)
        echo "<li>".sprintf("%04d",$a)."</li>\n";
    echo "</ul>\n";
}
?>

<p> Désolé pour le désagrément occasionné

<p> L'équipe de l'Agora