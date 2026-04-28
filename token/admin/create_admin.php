<?php
$mysqli = new mysqli("localhost","root","","token_system");
$password_hashed = password_hash("123456", PASSWORD_BCRYPT);

$stmt = $mysqli->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$username = "admin";
$stmt->bind_param("ss", $username, $password_hashed);
$stmt->execute();
$stmt->close();
$mysqli->close();
echo "Admin inserted successfully!";
?>