<?php
require 'autoload.php';
DotEnv::load();

/*
 * callback de Payconiq sur paiement traité
 */

 $payload = json_decode(file_get_contents("php://input"));
 if (json_last_error() !== JSON_ERROR_NONE) {
     echo "JSON decoding error: " . json_last_error_msg();
     die();
 }

$T=Txn::load($payload->paymentId);

if($T){
    $T->processAsPaid();
}