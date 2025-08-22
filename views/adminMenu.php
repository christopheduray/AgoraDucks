<div>
    <a class='btn btn-primary' href='<?=$BASE?>'>Vente directe</a>
    <a class='btn btn-primary' href='<?=$BASE?>admin/txn'>Transactions</a>
    <a class='btn btn-primary' href='<?=$BASE?>admin/ducks'>Visu canards</a>
</div>

<div>
<table class='table'>
    <?php
    foreach($STATS as $S)
        echo "<tr><td>$S[statut]</td><td align=right>$S[cnt]</td></tr>\n"
    ?>
</table>

</div>