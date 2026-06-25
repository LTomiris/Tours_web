<?php
require "db.php";

if (!isLogin()) {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    $user_id = $_SESSION['user_id'];

    $check = $pdo->prepare("SELECT id FROM cart WHERE user_id=? AND product_id=?");
    $check->execute([$user_id, $product_id]);

    if ($check->fetch()) {
        $update = $pdo->prepare("UPDATE cart SET people_count = people_count + 1 WHERE user_id=? AND product_id=?");
        $update->execute([$user_id, $product_id]);
    } else {
        $insert = $pdo->prepare("INSERT INTO cart(user_id, product_id, people_count) VALUES(?,?,1)");
        $insert->execute([$user_id, $product_id]);
    }
}

header("Location: cart.php");
exit;
?>