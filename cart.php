<?php
session_start();
require 'connection.php';

if (!isset($_SESSION['email'])) {
    header('location: login.php');
    exit();
}

$user_id = $_SESSION['id'];
$query = "SELECT it.id, it.name, it.price 
          FROM users_items ut 
          INNER JOIN items it ON it.id = ut.item_id 
          WHERE ut.user_id = ? AND ut.status = 'Added to cart'";

$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$sum = 0;
$items = [];

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $sum += $row['price'];
}

if (count($items) === 0) {
    echo "<script>alert('No items in the cart!!'); window.location.href='products.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="img/lifestyleStore.png" />
    <title>Your Cart | Peachy Petal</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" type="text/css">
    <script src="bootstrap/js/jquery-3.2.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
    <?php require 'header.php'; ?>
    <div class="container">
        <h2>Your Shopping Cart</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th><th>Item Name</th><th>Price</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $index => $item): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>Rs <?= $item['price'] ?>/-</td>
                    <td><a href="cart_remove.php?id=<?= $item['id'] ?>" class="btn btn-danger btn-sm">Remove</a></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td></td><td><strong>Total</strong></td><td><strong>Rs <?= $sum ?>/-</strong></td>
                    <td><a href="success.php?id=<?= $user_id ?>" class="btn btn-primary">Confirm Order</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
