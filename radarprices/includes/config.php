<?php
// includes/config.php - DEĞİŞTİRME

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// VERITABANI BAĞLANTISI
define('DB_HOST', 'localhost');
define('DB_NAME', 'radarprices_db');
define('DB_USER', 'root');
define('DB_PASS', '');

$pdo = null;
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    error_log("DATABASE HATASI: " . $e->getMessage());
    $pdo = null;
}

// SITE AYARLARI
define('SITE_URL', 'http://localhost/radarprices');
define('SITE_NAME', 'RadarPrices.com');
define('SITE_TAGLINE', 'Akıllı Fiyat Karşılaştırma');
define('CURRENT_YEAR', date('Y'));

// HATA RAPORLAMA
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>