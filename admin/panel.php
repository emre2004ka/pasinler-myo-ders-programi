<?php
/**
 * Yonetim Paneli - Ana Sayfa
 */

session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: giris.php');
    exit;
}

require_once '../classes/CRUD.php';

$hocaCrud = new CRUD($db, 'hocalar');
$dersCrud = new CRUD($db, 'dersler');
$sinifCrud = new CRUD($db, 'siniflar');

$toplam_hocalar = $hocaCrud->toplamSayi();
$toplam_dersler = $dersCrud->toplamSayi();
$toplam_siniflar = $sinifCrud->toplamSayi();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yonetim Paneli</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #ecf0f1; }
        .admin-container { display: grid; grid-template-columns: 250px 1fr; min-height: 100vh; }
        .admin-sidebar { background: #2c3e50; padding: 20px; color: white; }
        .admin-sidebar h2 { margin-bottom: 30px; font-size: 18px; }
        .admin-sidebar ul { list-style: none; }
        .admin-sidebar li { margin-bottom: 10px; }
        .admin-sidebar a { color: #bdc3c7; text-decoration: none; display: block; padding: 10px 15px; border-radius: 5px; }
        .admin-sidebar a:hover { background: #667eea; color: white; }
        .admin-content { background: #ecf0f1; padding: 30px; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; background: white; padding: 20px; border-radius: 10px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .stat-card { background: white; padding: 25px; border-radius: 10px; border-left: 4px solid #667eea; text-align: center; }
        .stat-card .number { font-size: 32px; color: #2c3e50; font-weight: bold; margin: 10px 0; }
        .stat-card .label { color: #7f8c8d; font-size: 14px; }
        .user-info a { background: #e74c3c; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: 600; }
        .user-info a:hover { background: #c0392b; }
    </style>
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <h2>YONETIM PANELI</h2>
            <ul>
                <li><a href="panel.php">Ana Sayfa</a></li>
                <li><a href="hocalar/">Hocalar</a></li>
                <li><a href="dersler/">Dersler</a></li>
                <li><a href="siniflar/">Siniflar</a></li>
                <li><a href="program/">Program Hazirla</a></li>
                <li><a href="cakismalar/">Cakismalar</a></li>
            </ul>
        </aside>
        
        <main class="admin-content">
            <div class="admin-header">
                <h1>Kontrol Paneli</h1>
                <div class="user-info">
                    <span>Hos geldin: <strong><?php echo htmlspecialchars($_SESSION['admin_ad_soyad']); ?></strong></span>
                    <a href="cikis.php">Cikis</a>
                </div>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="number"><?php echo $toplam_hocalar; ?></div>
                    <div class="label">Toplam Hoca</div>
                </div>
                <div class="stat-card">
                    <div class="number"><?php echo $toplam_dersler; ?></div>
                    <div class="label">Toplam Ders</div>
                </div>
                <div class="stat-card">
                    <div class="number"><?php echo $toplam_siniflar; ?></div>
                    <div class="label">Toplam Sinif</div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
