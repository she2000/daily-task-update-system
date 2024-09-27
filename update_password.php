<?php
include '../config/db.php'; // Ensure the path to db.php is correct

// Set the password you want to update
$newPassword = 'adminpassword123';  // Password you want to set for the user
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// Update the admin user's password
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
$stmt->execute([$hashedPassword, 'admin']);  // Change 'admin' to 'user1' or 'user2' if needed

echo "Password has been updated!";
?>
