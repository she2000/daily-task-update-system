<?php
$adminPassword = password_hash('adminpassword123', PASSWORD_DEFAULT);
$user1Password = password_hash('userpassword1', PASSWORD_DEFAULT);
$user2Password = password_hash('userpassword2', PASSWORD_DEFAULT);

echo "Admin Password Hash: " . $adminPassword . "<br>";
echo "User 1 Password Hash: " . $user1Password . "<br>";
echo "User 2 Password Hash: " . $user2Password . "<br>";
?>
