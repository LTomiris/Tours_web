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
    <h1>Travel Dream</h1>
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
    Travel Dream Agency © 2026
</footer>

</body>
</html>