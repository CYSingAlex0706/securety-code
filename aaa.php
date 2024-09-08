<?php
// encryption_handler.php
$plaintext = "Confidential";
// Encryption settings
define('CIPHER', 'aes-256-cbc');
define('ENCRYPTION_KEY', '12345678123456781234567812345678'); // This should be a secure key, stored safely

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
    //echo "<h2>" . $decrypted . "<h2>";
    return openssl_decrypt($decrypted, CIPHER, ENCRYPTION_KEY, 0, $iv);
}

// echo "<h2>" .  . "<h2>";
$a = encryptPassword($plaintext);
$a1 = encryptPassword($plaintext);
echo "<h2> encryptPassword :  " . $a . "<h2>";
echo "<h2> encryptPassword1 :  " . $a1 . "<h2>";
echo "<h2> decryptPassword :  " . decryptPassword($a) . "<h2>";
echo "<h2> decryptPassword :  " . decryptPassword($a1) . "<h2>";


// 用戶的原始密碼
$password = "my_secure_password";

// 使用 password_hash 函數生成包含鹽值的雜湊密碼
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// 將雜湊密碼儲存在資料庫中
echo "雜湊後的密碼: " . $hashedPassword . "</br>";

// 用戶登錄時輸入的密碼
$inputPassword = "my_secure_password";

// 使用 password_verify 函數驗證用戶輸入的密碼
if (password_verify($inputPassword, $hashedPassword)) {
    echo "密碼正確！ </br>";
} else {
    echo "密碼錯誤！";
}

$message = "8fr5dt87";
$hash_value_sha256 = hash("sha256", $message);
$hash_value_sha256_2 = hash("sha256", $message);

echo "hash後的密碼: " . $hash_value_sha256 . "</br>";
echo "hash後的密碼2: " . $hash_value_sha256_2 . "</br>";
?>