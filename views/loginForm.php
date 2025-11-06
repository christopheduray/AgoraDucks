<h2>Accès à l'interface BO</h2>

<div class='card'>
    <div class='flare'>
        <p>Entrez votre mot de passe pour accéder aux fonctionnalités BO
        <?= $errMsg?"<div class=error>$errMsg</div>":"" ?>
        <form method=POST style='text-align: center;'>
            <input type=hidden name='action' value='login'>
            <input type=password name='password'>
            <button class='cta'>Login</button>
        </form>
    </div>
</div>