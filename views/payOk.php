<?php

switch($statut){
    case '0':
            
                echo "<div class='info'>Nous vérifions ton paiement...</div>";
                echo "<script>
                        setTimeout(function(){
                                document.location.reload()
                        },1200)
                </script>";

            break;
    case '1':
            echo "      <h1>Merci</h1>
                        <p>Nous avons bien reçu ton paiement! 
                        Tes certificats t'ont été envoyés par mail, tu les trouveras également ci-dessous
                        <p>Au plaisir de te retrouver ce ".Config::EVENT_DATE."\n";

            foreach($ducks as $D){
                echo $D->renderHTML();
            }

            break;
    case '2':
            echo "<div class=error>La transaction a été annulée\n</div>";
            break;
}
?>
<a class='cta' href='<?=$BASE?>'>Retour à l'interface de commande</a>