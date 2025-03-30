<?php
use DB;

function encryptResponse($data){

    $jsonData = json_encode($data);
    
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($jsonData, 'aes-256-cbc', '252e80b4e5d9cfc8b369ad98dcc87b5f', 0, $iv);
    return base64_encode($encrypted . '::' . base64_encode($iv));
}

function decryptRequest($encryptedData){   
    $decodedData = base64_decode($encryptedData);

    $iv = substr($decodedData, 0, 16);
    $ciphertext = substr($decodedData, 16);

    return openssl_decrypt($ciphertext, 'AES-256-CBC', '252e80b4e5d9cfc8b369ad98dcc87b5f', OPENSSL_RAW_DATA, $iv);

}


?>