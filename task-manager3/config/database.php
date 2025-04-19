<?php
$host = 'localhost';
$dbname = 'advanced_task_manager';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
session_start();
?>