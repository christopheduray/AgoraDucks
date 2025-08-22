<!doctype html>
<html>
<head>
<style>
@page {
    margin: 0;
}

html {
    margin: 5mm;
}
div.qr {
    display: inline-block;
    margin: 2mm;
    text-align: center;
    page-break-inside: avoid;
}
div.qr img {
    width: 18mm;
}
div.qr p {
    margin: -2mm 0 0 0;
    font-family: monospace;
    font-size: 20px;
    font-weight: bold;
}
    
</style>
</head>
<body>
<?php
foreach($files as $id=>$f){
    echo "<div class=qr>
        <img src='".$_ENV['PUBLIC_URL']."storage/$f'>
        <p>".sprintf("%04d",$id)."</p>
        </div>\n";
}
?>
    </body>
</html>