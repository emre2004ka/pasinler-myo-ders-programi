<?php
/**
 * Admin Giris Sayfasi
 */

session_start();
require_once '../config/database.php';

$hata = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $hata = 'Kullanici adi ve sifre gereklidir.';
    } else {
        $sql = "SELECT * FROM admin WHERE username = ? LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_ad_soyad'] = $admin['ad_soyad'];
            $_SESSION['oturum_zamani'] = time();
            header('Location: panel.php');
            exit;
        } else {
            $hata = 'Gecersiz kullanici adi veya sifre.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yonetim Paneli - Giris</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .login-container { display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .login-box { background: white; padding: 50px; border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); width: 100%; max-width: 400px; }
        .login-box h1 { color: #667eea; text-align: center; margin-bottom: 30px; font-size: 28px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; color: #333; font-weight: 600; }
        .form-group input { width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 16px; }
        .form-group input:focus { outline: none; border-color: #667eea; }
        .btn-login { width: 100%; padding: 12px; background: #667eea; color: white; border: none; border-radius: 5px; font-size: 16px; font-weight: 600; cursor: pointer; }
        .btn-login:hover { background: #5568d3; }
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; background: #fee; color: #c33; border: 1px solid #fcc; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Yonetim Paneli</h1>
            <?php if ($hata): ?>
                <div class="alert"><?php echo htmlspecialchars($hata); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="username">Kullanici Adi</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Parola</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-login">Giris Yap</button>
            </form>
            <p style="text-align: center; margin-top: 20px; font-size: 12px; color: #999;">Test: admin / admin123</p>
        </div>
    </div>
</body>
</html>
