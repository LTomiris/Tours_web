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
