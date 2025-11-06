<?php
require __DIR__.'/../autoload.php';

DotEnv::load();

/*
|  89 | annesophie.derrey@gmail.com  |
|  90 | katyjean73@gmail.com         |
|  91 | guisgandcoralie7@gmail.com   |
|  93 | maureenslx@hotmail.com       |
|  95 | payel.mariannick@hotmail.fr  |
|  97 | maureenslx@hotmail.com       |
|  98 | Aureliemathieu40@gmail.com   |
| 100 | Aureliemathieu40@gmail.com   |
| 107 | maureenslx@hotmail.com       |
| 114 | opheliedecock254@gmail.com   |
| 118 | opheliedecock254@gmail.com   |
| 133 | annabel.mariaule@gmail.com   |
| 134 | Edith.robert@outlook.be      |
| 136 | Edith.robert@outlook.be      |
| 137 | coline.pettiaux@gmail.com    |
| 138 | cecile_lambotte@hotmail.fr   |
| 139 | verostlouis66@hotmail.fr     |
| 144 | Ashley_jordan01@hotmail.com  |
| 146 | celine.vaneukem@hotmail.be   |
| 161 | Merald.donnet@hotmail.com    |
| 162 | severinedeschaep@yahoo.fr    |
| 163 | delphedumont@yahoo.fr        |
| 178 | anne.mauroit@live.be         |
| 192 | marienachtergael17@gmail.com |
| 193
*/

foreach([89,90,91,93,95,97,98,100,107,114,118,133,134,136,137,138,139,144,146,161,162,163,178,192,193] as $id){
    $T=Txn::load($id);
    if($T){
        $T->statut=2;
        $T->save();
        $ducks=Duck::fromTxn($T->id);
        foreach($ducks as $D) $D->free();
    }
}

