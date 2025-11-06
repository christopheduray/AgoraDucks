<?php
$BASE=Fwk\Router::$baseDir;
?><!doctype html>
<html lang="fr-FR" xml:lang="fr-FR" xmlns= "http://www.w3.org/1999/xhtml">
<head>
    <title>Choisis tes canards</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta http-equiv="Content-Language" content="fr">
    <link href='<?=$BASE?>/css/style.css?v=<?= date('YmdHis') ?>' rel='stylesheet'>

    <script src='https://code.jquery.com/jquery-3.7.1.min.js'></script>

    <?php
        if($_SESSION['ADMIN']??false){
    ?>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <?php
        }
    ?>


</head>
<body>
<?= ($_SESSION['ADMIN']??false)?"<a class='btn btn-primary' href='".$BASE."admin'>Menu Admin</a>":"<img id=logo src='".$BASE."images/logo_cercle.png'>" ?>
<div class='content'>
