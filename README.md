# 🌍 Tours — Туристический сайт

Учебный проект — платформа для бронирования туров.

## Стек
- MySQL (база данных)
- PHP / HTML / CSS

## База данных включает
- Пользователи (регистрация, роли, баланс)
- Туры (страна, город, отель, питание, цена)
- Категории туров
- Корзина и заказы
- Сообщения обратной связи

## Структура таблиц
- `users` — пользователи и администраторы
- `products` — туры с деталями
- `categories` — типы туров
- `cart` — корзина
- `orders` / `order_items` — заказы
- `messages` — форма обратной связи

## SQL-схема базы данных

CREATE DATABASE IF NOT EXISTS travel_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE travel_db;

DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(30),
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    balance DECIMAL(12,2) DEFAULT 700000
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    country VARCHAR(100),
    city VARCHAR(100),
    category_id INT,
    hotel VARCHAR(150),
    stars INT,
    food VARCHAR(100),
    room_type VARCHAR(100),
    start_date DATE,
    end_date DATE,
    days INT,
    flight_time VARCHAR(100),
    price DECIMAL(12,2),
    discount_price DECIMAL(12,2),
    image TEXT,
    description TEXT,
    is_hot TINYINT DEFAULT 0
);

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    people_count INT DEFAULT 1
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_price DECIMAL(12,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    people_count INT,
    price DECIMAL(12,2)
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categories(name) VALUES
('Пляжный отдых'),
('Экскурсионный тур'),
('Горный тур'),
('Luxury отдых');

INSERT INTO users(name,email,phone,password,role,balance)
VALUES
('Admin','admin@mail.com','87000000000','12345','admin',10000000);

INSERT INTO products
(title,country,city,category_id,hotel,stars,food,room_type,start_date,end_date,days,flight_time,price,discount_price,image,description,is_hot)
VALUES
('Анталия 5★ All Inclusive','Турция','Анталия',1,'Royal Seginus Hotel',5,'All Inclusive','Standard','2026-06-10','2026-06-17',7,'08:30 Алматы — Анталия',350000,299000,'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=80','Теңіз, жағажай, 5 жұлдызды отель.',1),

('Шарм-эль-Шейх','Египет','Шарм-эль-Шейх',1,'Rixos Premium Seagate',5,'All Inclusive','Family','2026-06-15','2026-06-21',6,'10:00 Алматы — Шарм',310000,260000,'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=900&q=80','Қызыл теңіз, дайвинг, жағажай.',1),

('Париж туры','Франция','Париж',2,'Mercure Paris Centre',4,'Breakfast','Lux','2026-07-05','2026-07-10',5,'06:20 Алматы — Париж',600000,550000,'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=900&q=80','Эйфель мұнарасы, Лувр, қала серуені.',0),

('Дубай Luxury','БАӘ','Дубай',4,'Atlantis The Palm',5,'All Inclusive','Premium','2026-06-25','2026-06-30',5,'04:50 Алматы — Дубай',820000,750000,'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=900&q=80','Luxury отель, шопинг, теңіз.',1),

('Рим экскурсиясы','Италия','Рим',2,'Hotel Artemide',4,'Breakfast','Standard','2026-07-12','2026-07-18',6,'07:45 Алматы — Рим',580000,520000,'https://images.unsplash.com/photo-1552832230-c0197dd311b5?auto=format&fit=crop&w=900&q=80','Колизей, Ватикан, тарихи орындар.',0),

('Барселона демалысы','Испания','Барселона',1,'Barcelona Princess',4,'Half Board','Lux','2026-07-20','2026-07-27',7,'09:30 Алматы — Барселона',640000,590000,'https://images.unsplash.com/photo-1583422409516-2895a77efded?auto=format&fit=crop&w=900&q=80','Жағажай, қала, архитектура.',1),

('Бали аралы','Индонезия','Бали',1,'Bali Garden Resort',5,'Breakfast','Family','2026-08-05','2026-08-15',10,'23:10 Алматы — Бали',890000,820000,'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=900&q=80','Тропикалық арал, табиғат, жағажай.',1),

('Токио туры','Жапония','Токио',2,'Shinjuku Granbell Hotel',4,'Breakfast','Standard','2026-09-01','2026-09-08',7,'01:20 Алматы — Токио',780000,720000,'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?auto=format&fit=crop&w=900&q=80','Заманауи қала, мәдениет, технология.',0),

('Мальдив аралдары','Мальдив','Мале',4,'Paradise Island Resort',5,'All Inclusive','Premium','2026-08-10','2026-08-17',7,'05:40 Алматы — Мале',1200000,1090000,'https://images.unsplash.com/photo-1514282401047-d79a71a590e8?auto=format&fit=crop&w=900&q=80','Ақ құм, мұхит, вилла, luxury демалыс.',1),

('Ыстықкөл демалысы','Қырғызстан','Ыстықкөл',3,'Karven Four Seasons',4,'Half Board','Family','2026-06-20','2026-06-25',5,'Автобус: Алматы — Ыстықкөл',180000,150000,'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80','Көл, тау, отбасылық демалыс.',0),

('Сеул туры','Оңтүстік Корея','Сеул',2,'Lotte Hotel Seoul',5,'Breakfast','Lux','2026-09-10','2026-09-17',7,'03:50 Алматы — Сеул',870000,810000,'https://images.unsplash.com/photo-1549692520-acc6669e2f0c?auto=format&fit=crop&w=900&q=80','K-pop, заманауи қала, корей мәдениеті.',1),

('Нью-Йорк туры','АҚШ','Нью-Йорк',2,'The Plaza Hotel',5,'Breakfast','Premium','2026-10-01','2026-10-10',10,'07:30 Алматы — Нью-Йорк',1400000,1290000,'https://images.unsplash.com/photo-1499092346589-b9b6be3e94b2?auto=format&fit=crop&w=900&q=80','Times Square, Manhattan, Американың атмосферасы.',1);
<?php
require "db.php";

$message = "";
$error = "";

if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    $check = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $check->execute([$email]);

    if ($check->fetch()) {
        $error = "Бұл email бұрын тіркелген!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users(name,email,phone,password,balance) VALUES(?,?,?,?,700000)");
        $stmt->execute([$name, $email, $phone, $password]);
        $message = "Тіркелу сәтті өтті! Енді кіріңіз.";
    }
}

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? AND password=?");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Email немесе құпиясөз қате!";
    }
}

if (isset($_POST['contact'])) {
    $stmt = $pdo->prepare("INSERT INTO messages(name,email,message) VALUES(?,?,?)");
    $stmt->execute([$_POST['cname'], $_POST['cemail'], $_POST['cmessage']]);
    $message = "Хабарлама жіберілді!";
}

$currentUser = null;

if (isLogin()) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$_SESSION['user_id']]);
    $currentUser = $stmt->fetch();

    if (!$currentUser) {
        session_destroy();
        header("Location: index.php");
        exit;
    }
}

$query = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($_GET['country'])) {
    $query .= " AND country LIKE ?";
    $params[] = "%" . $_GET['country'] . "%";
}

if (!empty($_GET['category'])) {
    $query .= " AND category_id=?";
    $params[] = $_GET['category'];
}

if (!empty($_GET['max_price'])) {
    $query .= " AND discount_price <= ?";
    $params[] = $_GET['max_price'];
}

if (!empty($_GET['days'])) {
    $query .= " AND days=?";
    $params[] = $_GET['days'];
}

if (!empty($_GET['stars'])) {
    $query .= " AND stars=?";
    $params[] = $_GET['stars'];
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$tours = $stmt->fetchAll();

$hotTours = $pdo->query("SELECT * FROM products WHERE is_hot=1 LIMIT 4")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <title>Travel Dream</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>✈️ Travel Dream</h1>
    <nav>
        <a href="index.php">Басты бет</a>
        <a href="#tours">Турлар</a>
        <a href="cart.php">Себет</a>

        <?php if(isLogin()): ?>
            <a href="profile.php">Профиль</a>

            <?php if(isAdmin()): ?>
                <a href="admin.php">Админ</a>
            <?php endif; ?>

            <a href="logout.php">Шығу</a>
        <?php endif; ?>
    </nav>
</header>

<section class="hero">
    <h2>Әлем бойынша дайын турлар</h2>
    <p>Баға, отель, уақыт, ел және демалыс түрі бойынша таңдаңыз</p>
    <a href="#tours" class="btn">Турларды көру</a>
</section>

<div class="container">

<?php if($message): ?>
    <div class="success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php if($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if(!isLogin()): ?>
<section class="auth">
    <div class="box">
        <h2>Тіркелу</h2>
        <form method="POST">
            <input name="name" placeholder="Атыңыз" required>
            <input type="email" name="email" placeholder="Email" required>
            <input name="phone" placeholder="Телефон" required>
            <input type="password" name="password" placeholder="Құпиясөз" required>
            <button name="register">Тіркелу</button>
        </form>
    </div>

    <div class="box">
        <h2>Кіру</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Құпиясөз" required>
            <button name="login">Кіру</button>
        </form>
    </div>
</section>
<?php else: ?>
<div class="profile-mini">
    Қош келдіңіз, <b><?= htmlspecialchars($currentUser['name']) ?></b> |
    Баланс: <b><?= number_format($currentUser['balance'],0) ?> ₸</b>
</div>
<?php endif; ?>

<section>
    <h2 class="section-title">🔥 Ыстық турлар</h2>
    <div class="hot-grid">
        <?php foreach($hotTours as $tour): ?>
            <div class="hot-card">
                <img src="<?= htmlspecialchars($tour['image']) ?>">
                <h3><?= htmlspecialchars($tour['title']) ?></h3>
                <p><?= number_format($tour['discount_price'],0) ?> ₸</p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="popular">
    <h2 class="section-title">🌍 Танымал елдер</h2>
    <div class="popular-grid">
        <div>🇹🇷 Турция</div>
        <div>🇪🇬 Египет</div>
        <div>🇫🇷 Франция</div>
        <div>🇦🇪 Дубай</div>
        <div>🇮🇹 Италия</div>
        <div>🇪🇸 Испания</div>
        <div>🇮🇩 Индонезия</div>
        <div>🇯🇵 Жапония</div>
        <div>🇲🇻 Мальдив</div>
        <div>🇰🇬 Қырғызстан</div>
        <div>🇰🇷 Оңтүстік Корея</div>
        <div>🇺🇸 АҚШ</div>
    </div>
</section>

<section class="filter" id="tours">
    <h2>Турларды фильтрлеу</h2>

    <form method="GET">
        <input name="country" placeholder="Ел бойынша">

        <select name="category">
            <option value="">Демалыс түрі</option>
            <?php foreach($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <input type="number" name="max_price" placeholder="Макс. баға">

        <select name="days">
            <option value="">Күн саны</option>
            <option value="5">5 күн</option>
            <option value="6">6 күн</option>
            <option value="7">7 күн</option>
            <option value="10">10 күн</option>
        </select>

        <select name="stars">
            <option value="">Отель жұлдызы</option>
            <option value="3">3★</option>
            <option value="4">4★</option>
            <option value="5">5★</option>
        </select>

        <button>Іздеу</button>
    </form>
</section>

<section class="tours">
<?php foreach($tours as $tour): ?>
    <div class="card">
        <img src="<?= htmlspecialchars($tour['image']) ?>">

        <div class="card-body">
            <h3><?= htmlspecialchars($tour['title']) ?></h3>
            <p><?= htmlspecialchars($tour['description']) ?></p>

            <p><b>Ел:</b> <?= htmlspecialchars($tour['country']) ?>, <?= htmlspecialchars($tour['city']) ?></p>
            <p><b>Отель:</b> <?= htmlspecialchars($tour['hotel']) ?> <?= htmlspecialchars($tour['stars']) ?>★</p>
            <p><b>Тамақтану:</b> <?= htmlspecialchars($tour['food']) ?></p>
            <p><b>Бөлме:</b> <?= htmlspecialchars($tour['room_type']) ?></p>
            <p><b>Ұшу күні:</b> <?= htmlspecialchars($tour['start_date']) ?></p>
            <p><b>Қайту күні:</b> <?= htmlspecialchars($tour['end_date']) ?></p>
            <p><b>Рейс:</b> <?= htmlspecialchars($tour['flight_time']) ?></p>
            <p><b>Ұзақтығы:</b> <?= htmlspecialchars($tour['days']) ?> күн</p>

            <p class="old-price"><?= number_format($tour['price'],0) ?> ₸</p>
            <h2><?= number_format($tour['discount_price'],0) ?> ₸</h2>

            <?php if(isLogin()): ?>
                <a class="btn" href="add_to_cart.php?id=<?= $tour['id'] ?>">Себетке салу</a>
            <?php else: ?>
                <p class="need-login">Сатып алу үшін аккаунтқа кіріңіз</p>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
</section>

<section class="reviews">
    <h2 class="section-title">⭐ Пікірлер</h2>
    <div class="review-grid">
        <div>“Турция туры өте керемет болды!” — Айдана</div>
        <div>“Сайт ыңғайлы, бәрі түсінікті.” — Ержан</div>
        <div>“Дубайдағы отель өте ұнады.” — Мадина</div>
    </div>
</section>

<section class="about">
    <h2>Біз туралы</h2>
    <p>
        Travel Dream — елдер мен демалыс түрлері бойынша тур таңдауға арналған туристік агенттік сайты.
        Қолданушы тіркеліп, турды себетке қосып, баланс арқылы сатып ала алады.
    </p>
</section>

<section class="faq">
    <h2>FAQ сұрақ-жауап</h2>

    <details>
        <summary>Турды қалай сатып аламын?</summary>
        <p>Аккаунтқа кіріп, турды себетке қосып, сатып алу батырмасын басасыз.</p>
    </details>

    <details>
        <summary>Ақша қалай шешіледі?</summary>
        <p>Сатып алу кезінде жалпы сумма баланстан автоматты түрде шешіледі.</p>
    </details>

    <details>
        <summary>Адам санын таңдауға бола ма?</summary>
        <p>Иә, себет ішінде + және − батырмасы арқылы адам санын өзгерте аласыз.</p>
    </details>
</section>

<section class="contact">
    <h2>Байланыс формасы</h2>
    <form method="POST">
        <input name="cname" placeholder="Атыңыз" required>
        <input type="email" name="cemail" placeholder="Email" required>
        <textarea name="cmessage" placeholder="Хабарлама" required></textarea>
        <button name="contact">Жіберу</button>
    </form>
</section>

</div>

<footer>
    Travel Dream Agency ©️ 2026
</footer>

</body>
</html>
