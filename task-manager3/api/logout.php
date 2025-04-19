<?php
require '../config/database.php';
session_destroy();
echo json_encode(["message" => "Выход выполнен"]);
?>