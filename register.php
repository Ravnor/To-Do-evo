<?php

declare (strict_types= 1);

session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

    try {
        $stmt->execute([$username, $password]);
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        $error = "Nombre de usuario ya existe";
    }
}
?>
