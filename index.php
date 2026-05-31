<?php
/**
 * Pasinler MYO Ders Programı Yönetim Sistemi
 * Ana Giriş Sayfası
 * 
 * Kaynak: https://atauni.edu.tr/pasinler-meslek-yuksekokulu/
 */

session_start();

// Veritabanı bağlantısı
require_once 'config/database.php';

// Hata raporlamayı aç (Geliştirme aşamasında)
ini_set('display_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasinler MYO - Ders Programı Yönetim Sistemi</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navigasyon Başlığı -->
    <header class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <h1><i class="fas fa-calendar-alt"></i> Pasinler MYO Ders Programı</h1>
            </div>
            <nav class="navbar-menu">
                <a href="index.php" class="nav-link active">Ana Sayfa</a>
                <a href="admin/giris.php" class="nav-link"><i class="fas fa-lock"></i> Yönetim Paneli</a>
                <a href="#hakkinda" class="nav-link">Hakkında</a>
            </nav>
        </div>
    </header>

    <!-- Ana İçerik -->
    <main class="main-content">
        <div class="container">
            <!-- Hoş Geldiniz Bölümü -->
            <section class="welcome-section">
                <div class="welcome-content">
                    <h2>Hoş Geldiniz!</h2>
                    <p>Pasinler Meslek Yüksekokulu Ders Programı Yönetim Sistemi</p>
                    <p class="subtitle">Ders ve sınav programlarını dinamik olarak yönetin, çakışmaları otomatik kontrol edin.</p>
                </div>
            </section>

            <!-- Sistem Özellikleri -->
            <section class="features-section">
                <h3>Sistem Özellikleri</h3>
                <div class="features-grid">
                    <div class="feature-card">
                        <i class="fas fa-graduation-cap"></i>
                        <h4>Hoca Yönetimi</h4>
                        <p>Pasinler MYO akademik personelini ekleyin, düzenleyin ve silin</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-book"></i>
                        <h4>Ders Yönetimi</h4>
                        <p>Tüm dersleri, kodlarını, saatlerini ve hoçalarını yönetin</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-door-open"></i>
                        <h4>Sınıf Yönetimi</h4>
                        <p>Derslik ve laboratuvar bilgilerini kaydedin</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h4>Çakışma Kontrolü</h4>
                        <p>Hoca, sınıf ve program bazlı otomatik çakışma tespiti</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-calendar-days"></i>
                        <h4>Program Hazırlama</h4>
                        <p>Gün ve saatlere göre dersleri atayın</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-chart-bar"></i>
                        <h4>Raporlar</h4>
                        <p>Programı görüntüleyin, PDF olarak indirin</p>
                    </div>
                </div>
            </section>

            <!-- Pasinler MYO Bilgileri -->
            <section class="info-section">
                <h3>Pasinler Meslek Yüksekokulu</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Kuruluş</h4>
                        <p>Atatürk Üniversitesi bünyesinde</p>
                    </div>
                    <div class="info-card">
                        <h4>Bölümler</h4>
                        <p>10 Farklı Önlisans Programı</p>
                    </div>
                    <div class="info-card">
                        <h4>Akademik Personel</h4>
                        <p>2 Doçent, 4 Dr. Öğr. Üyesi, 9 Öğr. Gör.</p>
                    </div>
                    <div class="info-card">
                        <h4>Lokasyon</h4>
                        <p>Pasinler, Erzurum, Türkiye</p>
                    </div>
                </div>
            </section>

            <!-- Hızlı Erişim Butonları -->
            <section class="quick-access">
                <h3>Hızlı Erişim</h3>
                <div class="access-buttons">
                    <a href="admin/giris.php" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Yönetim Paneline Giriş
                    </a>
                    <a href="programlar/listele.php" class="btn btn-secondary">
                        <i class="fas fa-calendar-alt"></i> Program Görüntüle
                    </a>
                    <a href="raporlar/index.php" class="btn btn-secondary">
                        <i class="fas fa-file-pdf"></i> Raporlar
                    </a>
                </div>
            </section>

            <!-- Teknik Bilgiler -->
            <section class="tech-info">
                <h3>Teknik Detaylar</h3>
                <div class="tech-grid">
                    <div class="tech-item">
                        <strong>Dil:</strong> PHP (Native) + PDO
                    </div>
                    <div class="tech-item">
                        <strong>Veritabanı:</strong> MySQL
                    </div>
                    <div class="tech-item">
                        <strong>Frontend:</strong> HTML5 + CSS3 + JavaScript
                    </div>
                    <div class="tech-item">
                        <strong>Algoritma:</strong> Gelişmiş Çakışma Kontrol Motoru
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Pasinler Meslek Yüksekokulu. Tüm hakları saklıdır.</p>
            <p>Ders Programı Yönetim Sistemi | Atatürk Üniversitesi</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
