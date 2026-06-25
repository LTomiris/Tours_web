<?php
require "db.php";

if (!isLogin() || !isAdmin()) {
    header("Location: index.php");
    exit;
}

$message = "";

if (isset($_POST['add_tour'])) {
    $stmt = $pdo->prepare("
        INSERT INTO products(title,country,city,category_id,hotel,stars,food,room_type,start_date,end_date,days,flight_time,price,discount_price,image,description,is_hot)
        VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->execute([
        $_POST['title'], $_POST['country'], $_POST['city'], $_POST['category_id'],
        $_POST['hotel'], $_POST['stars'], $_POST['food'], $_POST['room_type'],
        $_POST['start_date'], $_POST['end_date'], $_POST['days'], $_POST['flight_time'],
        $_POST['price'], $_POST['discount_price'], $_POST['image'], $_POST['description'],
        isset($_POST['is_hot']) ? 1 : 0
    ]);

    $message = "Тур қосылды!";
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
    $stmt->execute([$id]);
    header("Location: admin.php");
    exit;
}

if (isset($_POST['update_price'])) {
    $stmt = $pdo->prepare("UPDATE products SET price=?, discount_price=? WHERE id=?");
    $stmt->execute([$_POST['price'], $_POST['discount_price'], $_POST['product_id']]);
    $message = "Баға өзгертілді!";
}

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$orders = $pdo->query("
    SELECT o.id, u.name, u.email, o.total_price, o.created_at
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.id DESC
")->fetchAll();
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
$messages = $pdo->query("SELECT * FROM messages ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>Админ панель</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>⚙️ Админ панель</h1>
    <nav>
        <a href="index.php">Сайтқа қайту</a>
        <a href="logout.php">Шығу</a>
    </nav>
</header>

<div class="container">

<?php if($message): ?><div class="success"><?= $message ?></div><?php endif; ?>

<section class="admin-section">
    <h2>Жаңа тур қосу</h2>
    <form method="POST" class="admin-form">
        <input name="title" placeholder="Тур атауы" required>
        <input name="country" placeholder="Ел" required>
        <input name="city" placeholder="Қала" required>

        <select name="category_id">
            <?php foreach($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
            <?php endforeach; ?>
        </select>

        <input name="hotel" placeholder="Отель атауы" required>

        <select name="stars">
            <option value="3">3★</option>
            <option value="4">4★</option>
            <option value="5">5★</option>
        </select>

        <select name="food">
            <option>All Inclusive</option>
            <option>Breakfast</option>
            <option>Half Board</option>
        </select>

        <select name="room_type">
            <option>Standard</option>
            <option>Lux</option>
            <option>Family</option>
        </select>

        <input type="date" name="start_date" required>
        <input type="date" name="end_date" required>
        <input type="number" name="days" placeholder="Күн саны" required>
        <input name="flight_time" placeholder="Рейс уақыты" required>
        <input type="number" name="price" placeholder="Негізгі баға" required>
        <input type="number" name="discount_price" placeholder="Жеңілдік бағасы" required>
        <input name="image" placeholder="Сурет URL" required>
        <textarea name="description" placeholder="Сипаттама"></textarea>

        <label>
            <input type="checkbox" name="is_hot"> Ыстық тур
        </label>

        <button name="add_tour">Тур қосу</button>
    </form>
</section>

<section class="admin-section">
    <h2>Турлар тізімі</h2>

    <?php foreach($products as $p): ?>
        <div class="admin-card">
            <b><?= htmlspecialchars($p['title']) ?></b>
            <p><?= $p['country'] ?> | <?= $p['hotel'] ?> | <?= $p['stars'] ?>★</p>

            <form method="POST" class="price-form">
                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                <input type="number" name="price" value="<?= $p['price'] ?>">
                <input type="number" name="discount_price" value="<?= $p['discount_price'] ?>">
                <button name="update_price">Бағаны өзгерту</button>
            </form>

            <a href="admin.php?delete=<?= $p['id'] ?>" class="delete">Өшіру</a>
        </div>
    <?php endforeach; ?>
</section>

<section class="admin-section">
    <h2>Заказдар</h2>
    <?php foreach($orders as $o): ?>
        <div class="order-item">
            <b>Заказ #<?= $o['id'] ?></b>
            <p><?= $o['name'] ?> | <?= $o['email'] ?></p>
            <p>Сумма: <?= number_format($o['total_price'],0) ?> ₸</p>
            <p>Уақыты: <?= $o['created_at'] ?></p>
        </div>
    <?php endforeach; ?>
</section>

<section class="admin-section">
    <h2>Қолданушылар</h2>
    <?php foreach($users as $u): ?>
        <div class="order-item">
            <b><?= htmlspecialchars($u['name']) ?></b>
            <p><?= $u['email'] ?> | <?= $u['phone'] ?></p>
            <p>Баланс: <?= number_format($u['balance'],0) ?> ₸</p>
            <p>Рөлі: <?= $u['role'] ?></p>
        </div>
    <?php endforeach; ?>
</section>

<section class="admin-section">
    <h2>Байланыс хабарламалары</h2>
    <?php foreach($messages as $m): ?>
        <div class="order-item">
            <b><?= htmlspecialchars($m['name']) ?></b>
            <p><?= $m['email'] ?></p>
            <p><?= htmlspecialchars($m['message']) ?></p>
        </div>
    <?php endforeach; ?>
</section>

</div>

</body>
</html>