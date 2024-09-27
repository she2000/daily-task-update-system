<?php
include '../config/db.php'; // Ensure the correct path to db.php

// Set the new user credentials
$username = 'newuser';   // Username you want to create
$plainPassword = 'newpassword123';   // Password you want to set for this user
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT); // Hash the password
$role = 'admin';  // Set the role of the user
$division = 'CodeWorks';  // Division or other additional info (adjust as needed)

// Insert the new user into the users table
$stmt = $pdo->prepare("INSERT INTO users (username, password, role, division) VALUES (?, ?, ?, ?)");
$stmt->execute([$username, $hashedPassword, $role, $division]);

echo "New user created successfully!";
?>
