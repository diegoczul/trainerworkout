<?php
$key = "1234567891011121"; // 16-byte key
$data = "Hello, World!";
$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);

$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encrypted, MCRYPT_MODE_CBC, $iv);

echo "Encrypted: " . base64_encode($encrypted) . "\n";
echo "Decrypted: " . trim($decrypted) . "\n";
?>

