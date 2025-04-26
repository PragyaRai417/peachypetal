<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['id']) || !isset($_GET['id'])) {
    header('location: products.php');
    exit();
}

$user_id = $_SESSION['id'];
$item_id = intval($_GET['id']);

$stmt = $con->prepare("INSERT INTO users_items(user_id, item_id, status) VALUES (?, ?, 'Added to cart')");
$stmt->bind_param("ii", $user_id, $item_id);
$stmt->execute();

header('location: products.php');
exit();
?>
