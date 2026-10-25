<?php
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}
require_once 'db_config.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM donuts WHERE donut_id = ?");
    $stmt->execute([$id]);
}

header("Location: index.php");
exit;
?>