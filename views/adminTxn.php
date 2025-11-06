<h2>Historique des transactions</h2>

<style>
    tr.cancel td {
        color: #999;
    }
</style>
<table class='table table-striped'>
    <thead>
        <tr><th>Id</th>
            <th>Date/heure</th>
            <th>GSM</th>
            <th>Email</th>
            <th>Canal</th>
            <th># canards</th>
            <th>Trace PayConiq</th>
        </tr>
    </thead>
    <tbody>
<?php
    foreach($TXN as $T){

        $payload=json_decode($T['payload'],1);
        $payTrace=json_decode($T['payment_trace']??'[]',1);
        $payLog="";
        if($payTrace && count($payTrace)) $payLog=$payTrace['status'].' - '.$payTrace['paymentId'];

        echo "
        <tr ".($T['statut']==1?'':"class='cancel'").">
            <td>$T[id]</td>
            <td>$T[ts]</td>
            <td>$T[gsm]</td>
            <td>$T[email]</td>
            <td>$payload[action]</td>
            <td align=right>$payload[nb]</td>
            <td>$payLog</td>
            <td>".(in_array($T['statut'],[0,1])?"<a class='btn btn-secondary'
                                        href='".$BASE."pay_check/?id_pay=$T[id]&token=$T[token]'>Visualiser</a>":"").
            "</td>
            <td>".($T['statut']==1?"<button class='btn btn-warning sendMail'
                                        data-id='$T[id]'
                                        >(r)Envoyer</button>":"")."</td>"
        ."</tr>
        ";
    }
?>
    </tbody>
</table>

<script>
    $('button.sendMail').click(function(){
        const t=$(this)
        const id=t.data('id')
        t.prop({ disabled: true })
        $.ajax({
            method: 'POST',
            url: '<?=$BASE?>/admin/ajax',
            dataType: 'json',
            data: {
                action: 'sendMail',
                id_txn: id
            },
            success: function(){
                alert("Email envoyé")
            }
        })

    })

</script>