<?php
require "db.php";

if (!isLogin()) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";
$type = "";

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id=? AND user_id=?");
    $stmt->execute([$id, $user_id]);
    header("Location: cart.php");
    exit;
}

if (isset($_GET['plus'])) {
    $id = (int)$_GET['plus'];
    $stmt = $pdo->prepare("UPDATE cart SET people_count=people_count+1 WHERE id=? AND user_id=?");
    $stmt->execute([$id, $user_id]);
    header("Location: cart.php");
    exit;
}

if (isset($_GET['minus'])) {
    $id = (int)$_GET['minus'];
    $stmt = $pdo->prepare("UPDATE cart SET people_count=GREATEST(1, people_count-1) WHERE id=? AND user_id=?");
    $stmt->execute([$id, $user_id]);
    header("Location: cart.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT c.id AS cart_id, c.people_count, p.*
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id=?
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();

$total = 0;
$total_people = 0;

foreach ($items as $item) {
    $total += $item['discount_price'] * $item['people_count'];
    $total_people += $item['people_count'];
}

if (isset($_POST['checkout'])) {
    if ($total <= 0) {
        $message = "Себет бос!";
        $type = "error";
    } else {
        $stmt = $pdo->prepare("SELECT balance FROM users WHERE id=?");
        $stmt->execute([$user_id]);
        $balance = $stmt->fetchColumn();

        if ($balance < $total) {
            $message = "Қаражат жеткіліксіз! Баланс: " . number_format($balance,0) . " ₸";
            $type = "error";
        } else {
            try {
                $pdo->beginTransaction();

                $stmt = $pdo->prepare("
                    INSERT INTO orders(user_id,total_price)
                    VALUES(?,?)
                ");
                $stmt->execute([$user_id, $total]);
                $order_id = $pdo->lastInsertId();

                foreach ($items as $item) {
                    $stmt = $pdo->prepare("
                        INSERT INTO order_items(order_id,product_id,people_count,price)
                        VALUES(?,?,?,?)
                    ");
                    $stmt->execute([
                        $order_id,
                        $item['id'],
                        $item['people_count'],
                        $item['discount_price']
                    ]);
                }

                $firstTour = $items[0];

                $stmt = $pdo->prepare("
                    UPDATE users
                    SET
                        balance = balance - ?,
                        tour_name = ?,
                        country = ?,
                        people_count = ?,
                        total_sum = ?,
                        departure_date = ?
                    WHERE id = ?
                ");

                $stmt->execute([
                    $total,
                    $firstTour['title'],
                    $firstTour['country'],
                    $total_people,
                    $total,
                    $firstTour['start_date'],
                    $user_id
                ]);

                $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id=?");
                $stmt->execute([$user_id]);

                $pdo->commit();

                header("Location: profile.php?success=1");
                exit;

            } catch (Exception $e) {
                $pdo->rollBack();
                $message = "Қате шықты: " . $e->getMessage();
                $type = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>Себет</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>🛒 Себет</h1>
    <nav>
        <a href="index.php">Басты бет</a>
        <a href="profile.php">Профиль</a>
        <a href="logout.php">Шығу</a>
    </nav>
</header>

<div class="container">
    <?php if($message): ?>
        <div class="<?= $type ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="cart-box">
        <h2>Себеттегі турлар</h2>

        <?php if(count($items) == 0): ?>
            <p>Себет бос.</p>
            <a href="index.php" class="btn">Тур таңдау</a>
        <?php endif; ?>

        <?php foreach($items as $item): ?>
        <div class="cart-item">
            <img src="<?= htmlspecialchars($item['image']) ?>">
            <div>
                <h3><?= htmlspecialchars($item['title']) ?></h3>
                <p><?= htmlspecialchars($item['hotel']) ?> <?= htmlspecialchars($item['stars']) ?>★</p>
                <p><?= number_format($item['discount_price'],0) ?> ₸ × <?= $item['people_count'] ?> адам</p>
                <b>Жалпы: <?= number_format($item['discount_price'] * $item['people_count'],0) ?> ₸</b>
            </div>
            <div>
                <a href="?minus=<?= $item['cart_id'] ?>" class="small">−</a>
                <a href="?plus=<?= $item['cart_id'] ?>" class="small">+</a>
                <a href="?delete=<?= $item['cart_id'] ?>" class="delete">Өшіру</a>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if(count($items) > 0): ?>
            <h2>Жалпы сумма: <?= number_format($total,0) ?> ₸</h2>
            <form method="POST">
                <button name="checkout" class="checkout">Сатып алу</button>
            </form>
        <?php endif; ?>
    </div>
</div>

</body>
</html>