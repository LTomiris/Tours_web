<?php
require "db.php";

if (!isLogin()) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$stmt = $pdo->prepare("
    SELECT o.id AS order_id, o.total_price, o.created_at,
           p.title, p.country, p.hotel, p.room_type, p.start_date, p.end_date,
           oi.people_count, oi.price
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.user_id=?
    ORDER BY o.id DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>Жеке аккаунт</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>👤 Жеке аккаунт</h1>
    <nav>
        <a href="index.php">Басты бет</a>
        <a href="cart.php">Себет</a>
        <a href="logout.php">Шығу</a>
    </nav>
</header>

<div class="container">

<?php if(isset($_GET['success'])): ?>
    <div class="success">Тур сәтті сатып алынды! Ақша баланстан шешілді.</div>
<?php endif; ?>

<div class="profile-card">
    <h2><?= htmlspecialchars($user['name']) ?></h2>
    <p><b>Email:</b> <?= htmlspecialchars($user['email']) ?></p>
    <p><b>Телефон:</b> <?= htmlspecialchars($user['phone']) ?></p>
    <p><b>Баланс:</b> <?= number_format($user['balance'],0) ?> ₸</p>
</div>

<div class="orders">
    <h2>Сатып алған турлар тарихы</h2>

    <?php if(count($orders) == 0): ?>
        <p>Әзірге сатып алынған тур жоқ.</p>
    <?php endif; ?>

    <?php foreach($orders as $order): ?>
        <div class="order-item">
            <h3><?= htmlspecialchars($order['title']) ?></h3>
            <p><b>Ел:</b> <?= $order['country'] ?></p>
            <p><b>Отель:</b> <?= $order['hotel'] ?></p>
            <p><b>Бөлме:</b> <?= $order['room_type'] ?></p>
            <p><b>Уақыты:</b> <?= $order['start_date'] ?> — <?= $order['end_date'] ?></p>
            <p><b>Адам саны:</b> <?= $order['people_count'] ?></p>
            <p><b>Баға:</b> <?= number_format($order['price'],0) ?> ₸</p>
            <p><b>Сатып алынған уақыт:</b> <?= $order['created_at'] ?></p>
        </div>
    <?php endforeach; ?>
</div>

</div>

</body>
</html>