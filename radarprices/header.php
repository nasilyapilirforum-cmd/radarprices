<?php
// header.php - TAM KOD
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RadarPrices - Akıllı Fiyat Karşılaştırma</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8fafc;
            line-height: 1.6;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        
        .logo span {
            color: #ffd700;
        }
        
        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .currency-selector, .language-selector {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            cursor: pointer;
        }
        
        .search-container {
            display: flex;
            gap: 10px;
            margin-bottom: 1rem;
        }
        
        .search-input {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            outline: none;
        }
        
        .search-btn {
            background: #ff6b6b;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .main-nav {
            display: flex;
            justify-content: center;
            gap: 25px;
            flex-wrap: wrap;
        }
        
        .nav-link {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 20px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .header-top {
                flex-direction: column;
                gap: 15px;
            }
            
            .main-nav {
                gap: 10px;
            }
            
            .nav-link {
                padding: 8px 12px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-top">
                <a href="index.php" class="logo">🚀 Radar<span>Prices</span></a>
                <div class="header-actions">
                    <select class="currency-selector">
                        <option>₺ Türk Lirası</option>
                        <option>$ Dolar</option>
                        <option>€ Euro</option>
                    </select>
                    <select class="language-selector">
                        <option>🇹🇷 TR</option>
                        <option>🇺🇸 EN</option>
                    </select>
                </div>
            </div>
            
            <form action="search.php" method="GET" class="search-container">
                <input type="text" name="q" class="search-input" placeholder="🔍 Ürün, marka veya kategori ara..." required>
                <button type="submit" class="search-btn">Ara</button>
            </form>
            
            <nav class="main-nav">
                <a href="category.php?cat=elektronik" class="nav-link">📱 Elektronik</a>
                <a href="category.php?cat=market" class="nav-link">🛒 Market</a>
                <a href="category.php?cat=ev-yasam" class="nav-link">🏠 Ev & Yaşam</a>
                <a href="category.php?cat=giyim" class="nav-link">👕 Giyim</a>
                <a href="category.php?cat=spor" class="nav-link">⚽ Spor</a>
                <a href="category.php?cat=kitap" class="nav-link">📚 Kitap</a>
                <a href="deals.php" class="nav-link">🔥 Fırsatlar</a>
            </nav>
        </div>
    </header>