<h2>Canards réservés</h2>

<style>
    div.duckBtn {
        display: inline-block;
        font-size: 1.4em;
        background-color: teal;
        color: white;
        padding: 5px 15px;
        margin: 5px 8px;
        border-radius: 5px;
        border: 2px solid white;
        cursor: pointer;
    }

    div.duckBtn:hover {
        border: 2px solid teal;
    }

    div.duckBtn.free {
        background-color: green;
    }

    div.duckBtn.reserved {
        background-color: orange;
    }

    div.duckBtn.paid {
        background-color: red;
    }

</style>

<?php

    $STATLUP=['free','reserved','paid'];
    foreach($DUCKS as $d){
        echo "<div class='duckBtn ".$STATLUP[$d['statut']]."'
                    data-id='$d[id]'>".sprintf("%04d",$d['id'])."</div>\n";
    }

?>

<script>
    const DUCKS=<?= json_encode([[],...$DUCKS]) ?>

    $('.duckBtn').click(function(){
        const id=$(this).data('id')
        const d=DUCKS[id]
        alert("Canard: "+d.id
                +"\n email: "+d.email
                +"\n gsm: "+d.gsm
                +"\n statut: "+d.statut
                +"\n ts: "+d.ts
                +"\n expiration: "+d.expiration_ts)
        console.log(DUCKS[id])
    })

</script>