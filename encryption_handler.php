<?php
// encryption_handler.php

$config = require 'config.php';
$CIPHER = $config['CIPHER'];
$encrypt_Key = $config['encrypt_Key'];

define('CIPHER', $CIPHER);
define('ENCRYPTION_KEY', $encrypt_Key); // This should be a secure key, stored safely

function encryptPassword($password) {
    $ivlen = openssl_cipher_iv_length(CIPHER);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $encrypted = openssl_encrypt($password, CIPHER, ENCRYPTION_KEY, 0, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptPassword($storedPassword) {
    $decoded = base64_decode($storedPassword);
    $ivlen = openssl_cipher_iv_length(CIPHER);
    $iv = substr($decoded, 0, $ivlen);
    $decrypted = substr($decoded, $ivlen);
    return openssl_decrypt($decrypted, CIPHER, ENCRYPTION_KEY, 0, $iv);
}

?>