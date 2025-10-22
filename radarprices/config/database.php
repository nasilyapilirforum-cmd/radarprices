<?php
// Veritabanı bağlantı ayarları
$host = 'localhost';
$dbname = 'radarprices';  // Veritabanı adı
$username = 'root';       // XAMPP default kullanıcı
$password = '';           // XAMPP default şifre (boş)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>