<?php
// admin/index.php - ADMIN GİRİŞ SAYFASI
session_start();

// EĞER ZATEN GİRİŞ YAPILMIŞSA DASHBOARD'A GİT
if (isset($_SESSION['admin'])) {
    header('Location: dashboard.php');
    exit();
}

// GİRİŞ FORMU GÖNDERİLDİ Mİ?
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // BASİT GİRİŞ KONTROLÜ
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin'] = $username;
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Hatalı kullanıcı adı veya şifre!";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RadarPrices - Admin Giriş</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo h1 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 5px;
        }
        .logo p {
            color: #7f8c8d;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e8e8e8;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #3498db;
        }
        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .login-btn:hover {
            transform: translateY(-2px);
        }
        .error-message {
            background: #ffeaa7;
            color: #d63031;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            border-left: 4px solid #d63031;
        }
        .demo-info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
            text-align: center;
            border-left: 4px solid #17a2b8;
        }
        .back-link {
            text-align: center;
            margin-top: 15px;
        }
        .back-link a {
            color: #3498db;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>🚀 RadarPrices</h1>
            <p>Admin Paneli Girişi</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="error-message">
                ❌ <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Kullanıcı Adı</label>
                <input type="text" id="username" name="username" placeholder="Kullanıcı adınızı girin" required value="admin">
            </div>

            <div class="form-group">
                <label for="password">Şifre</label>
                <input type="password" id="password" name="password" placeholder="Şifrenizi girin" required value="admin123">
            </div>

            <button type="submit" name="login" class="login-btn">
                🔐 Giriş Yap
            </button>
        </form>

        <div class="demo-info">
            <strong>Demo Bilgileri:</strong><br>
            Kullanıcı: <strong>admin</strong><br>
            Şifre: <strong>admin123</strong>
        </div>
        
        <div class="back-link">
            <a href="../">← Ana Sayfaya Dön</a>
        </div>
    </div>
</body>
</html>