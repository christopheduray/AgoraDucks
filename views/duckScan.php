<style>
    th {
        text-align: left;
    }
    th, td {
        padding: 5px 10px;
        border: 1px solid #ddd;
    }

    div.err {
        padding: 20px;
        background-color: red;
        color: white;
    }
</style>


<h1>Scan</h1>

<?php
if($DUCK){
?>
<table>
    <tr><th>Canard:</th><td><?= sprintf("%04d",$DUCK->id) ?></td></tr>
    <tr><th>Email:</th><td><?= $DUCK->email ?></td></tr>
    <tr><th>GSM:</th><td><?= $DUCK->gsm ?></td></tr>
    <tr><th>Token:</th><td><?= $DUCK->token ?></td></tr>
</table>
<?php
}
?>

<?php
    if($err) echo "<div class='err'>$err</div>";
?>

<h2>Classement</h2>

<input type=text id='id_duck' placeholder='(manuel)'>
<button class='btn btn-primary' id='manEncode'>Encoder</button>

<table class='table table-striped'>
    <tr><th>N°</th><th>Canard</th><th></th><th>Email</th><th>GSM</th></tr>
<?php
    foreach($SCANS as $S){
        echo "<tr><td>".$S->id."</td>
            <td>".sprintf("%04d",$S->id_duck)."</td>
            <td>
                <button class='btn btn-secondary btnUpd' data-id='".$S->id."' data-id_duck='".$S->id_duck."'>Upd</button>
            </td>
            <td>".$S->email."</td>
            <td>".$S->gsm."</td>
            </tr>\n";
    }
?>
</table>

<script>
    $('.btnUpd').click(function(){
        const id_duck=parseInt(prompt("Remplaçant"))
        const d=$(this).data()
        aj({
            action: 'updScan',
            id: d.id,
            id_duck: id_duck
        })
    })

    $('#manEncode').click(function(){
        $(this).prop({ disabled: true})
        aj({
            action: 'appendScan',
            id_duck: $('#id_duck').val()
        })
    })

function aj(payload, cb){
        $.ajax({
            method: 'POST',
            url: '<?=$BASE?>/admin/ajax',
            dataType: 'json',
            data: payload,
            success: function(){
                window.location.reload()
            }
        })
}
</script>