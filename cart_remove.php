<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['id']) || !isset($_GET['id'])) {
    header('location: cart.php');
    exit();
}

$user_id = $_SESSION['id'];
$item_id = intval($_GET['id']);

$stmt = $con->prepare("DELETE FROM users_items WHERE user_id = ? AND item_id = ?");
$stmt->bind_param("ii", $user_id, $item_id);
$stmt->execute();

header('location: cart.php');
exit();
?>
