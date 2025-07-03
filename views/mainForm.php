<h1>C'est par ici !!!</h1>

<div class=card>
    <div class=flare>
<p>Adopte ton ou tes canards sans traîner! 
    Tu peux même choisir ton numéro fétiche entre <span class=numero>0001</span>  
        et <span class='numero'><?= Config::MAXDUCKS ?></span>
<p>Pour la commande c'est super simple, tout se passe sur cette page. Tu renseignes ton numéro de téléphone 
    et ton email (c'est juste pour te prévenir si tu as gagné mais ne peux être présent, on ne t'enverra pas
    de spam, c'est promis), tu choisis le nombre de canards que tu désires et ... tu payes ;-)

<p>Ensuite, on t'invite à venir encourager ton ou tes canards le <b><?= Config::EVENT_DATE ?></b>
    </div>
</div>

<table class='dataEntry'>
    <tr><td>Mon email: </td><td><input type="text" class='wide' size=20 id="email"></td></tr>
    <tr><td>Mon GSM: </td><td><input type="text" class='wide' size=12 id="gsm"></td></tr>
    <tr><td>Je souhaite: </td><td><input type="text" size=1 value=1 readonly id="nb_canards" class="numeric">
    <button class='cta' id="cPlus">+</button><button class='cta' id="cMinus">-</button>
    canard(s)
    </td></tr>
</table>

<div class=card>
    <div class=flare>
<p>Pour tes canards, tu peux laisser le hasard choisir ou voir si tes numéros fétiches sont encore disponibles:
    </div>
</div>

<div class='by2'>
<div id="canards"></div>

<button id="goToPay" class='cta cta-primary'><span id=price></span> € ? C'est parti!</button>
</div>
<div class='by2'>
<div id=smilingDuck>
    <img src='images/duck_feelings.jpg'>
</div>
</div>

<div class='clr'></div>

<script>
let nbCanards=1
const MAXDUCKS=<?= Config::MAXDUCKS ?>

ajoutCanard(1)


const smDuck=$('#smilingDuck img').css({ top: -70, left: -20 })


function calcPrice(){
    const duckPos={
        1: { top: -70, left: -20 },
        2: { top: -70, left: -490 },
        3: { top: -70, left: -720 },
        4: { top: -390, left: -20 },
        5: { top: -390, left: -262 },
        6: { top: -390, left: -490 },
        7: { top: -390, left: -730 },
        8: { top: -700, left: -20 },
        9: { top: -700, left: -490 },
        10: { top: -700, left: -735 }
    }
    smDuck.css(duckPos[nbCanards])

    $('#price').html(<?= Config::PRICE ?>*nbCanards)



}
calcPrice()

$('#cPlus').click(()=>{
    if(nbCanards<10){
        nbCanards++
        ajoutCanard(nbCanards)
    }
    $('#nb_canards').val(nbCanards)
    calcPrice()
})
$('#cMinus').click(()=>{
    if(nbCanards>1){
        nbCanards--
        retireCanard()
    }
    $('#nb_canards').val(nbCanards)
    calcPrice()
})

function aj(payload, callback){
    $.ajax({
        method: 'POST',
        url: 'api.php',
        dataType: 'json',
        data: payload,
        success: callback
    })
}

function ajoutCanard(num){
    const parentDiv=$('#canards')
    const div=$('<div>').addClass('canard').appendTo(parentDiv)

    const errZone=$('<p>').addClass('errZone')

    $('<span>').addClass('duckNr').html(num).appendTo(div)

    const tWrapper=$('<label>').addClass('switch').appendTo(div)
    const toggler=$('<input type=checkbox>')
            .appendTo(tWrapper)
    $('<span>').addClass(['slider','round']).appendTo(tWrapper)

    const autoTxt=$('<span>').addClass('infoText')
                .html("Numéro automatique")
                .appendTo(div)
    const inp=$('<input type=text size=4>')
        .attr({ placeholder: 'choisis!'})
        .addClass('numeric')
        .change(()=>{
            let duckNr=parseInt(inp.val())
            if(isNaN(duckNr)) duckNr=''
            inp.val(duckNr)

            if(duckNr && duckNr>0 && duckNr<=MAXDUCKS){
                aj({
                    action: 'isAvailable',
                    duckNr: duckNr
                },(data)=>{
                    if(!data.available){
                            errZone.html('Ce numéro de canard est déjà affecté').show()
                            inp.addClass('error')
                        } else {
                            errZone.hide()
                            inp.removeClass('error')
                        }
                    }
                )
            } else {
                errZone.html("Merci de choisir un numéro valide entre 0001 et "+MAXDUCKS).show()
                inp.addClass('error')
            }
        })
        .appendTo(div)

    errZone.appendTo(div)

    toggler.change(()=>{
        if(toggler.prop('checked')){
            inp.addClass('personalDuck').show()
            autoTxt.hide()
            setTimeout(function(){ inp.focus().select() },100)
        } else {
            inp.val('').removeClass('personalDuck').hide()
            autoTxt.show()
        }
        errZone.hide()
    }).trigger('change')
}

function retireCanard(){
    $('#canards').children('div').last().remove()
}

function validateMail(str){
    if(str.length > 10 && str.match(/[a-z0-9\-\._]+@[a-z0-9\.]+\.[a-z0-9]+/i))
        return true
    else
        return false
}
function validatePhone(str){
    const numbers=str.replace(/[^0-9\+]/g,'')
    if(numbers.length < 9) return false
    else return true
}

$('#email').change(function(){
    const t=$(this)
    if(validateMail(t.val())) t.removeClass('error')
    else t.addClass('error')
})
$('#gsm').change(function(){
    const t=$(this)
    if(validatePhone(t.val())) t.removeClass('error')
    else t.addClass('error')
})

$('#goToPay').click(()=>{
    payload={
        action: 'order',
        nb: nbCanards,
        ducks: [],
        gsm: $('#gsm').val(),
        email: $('#email').val()
    }
    $('input.personalDuck').each(function(){
        const t=$(this)
        payload.ducks.push(t.val())
        if(!t.val()) t.addClass('error')
    })

    let errors=[]
    payload.ducks.map((d)=>{ if(d<1 || d>MAXDUCKS) errors=['Merci de remplir les numéros de canard correctement'] })

    if(!validateMail(payload.email)) errors.push("Le champ e-mail n'est pas correctement rempli")
    if(!validatePhone(payload.gsm)) errors.push("Le champ Téléphone n'est pas correctement rempli")

    if(errors.length>0){
        alert(errors.join("\n"))
        return false
    }

    aj(payload,(data)=>{
        if(data.state=='ok')
            document.location=data.url
        else {
            $('input.personalDuck').each(function(){
                const t=$(this)
                if(t.val()>'') t.trigger('change')
            })
            alert("Merci de remplir tous les champs correctement avant de procéder au paiement")
        }
    })   
    
})
</script>
