<?php
$password = "User123"; // Your normal password

// Hash the password using the default bcrypt algorithm
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Print the hashed password
echo $hashed_password;
?>

