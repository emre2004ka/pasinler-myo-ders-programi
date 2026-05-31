<?php
/**
 * Veritabanı Bağlantı Konfigürasyonu
 * PDO ile MySQL bağlantısı
 */

// Veritabanı parametreleri
define('DB_HOST', 'localhost');
define('DB_NAME', 'pasinler_myo');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// PDO bağlantısı
try {
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    
    // Türkçe karakter desteği
    $db->exec("SET NAMES utf8mb4");
    $db->exec("SET CHARACTER SET utf8mb4");
    
} catch (PDOException $e) {
    die("Veritabanı Bağlantı Hatası: " . $e->getMessage());
}

/**
 * Sistem Sabitleri
 */
define('SITE_NAME', 'Pasinler MYO Ders Programı');
define('SITE_URL', 'http://localhost/pasinler-myo-ders-programi');
define('ADMIN_URL', SITE_URL . '/admin');

// Session timeout (dakika)
define('SESSION_TIMEOUT', 30);

// Hata mesajları
define('ERROR_GENERIC', 'Bir hata oluştu. Lütfen tekrar deneyiniz.');
define('ERROR_DB', 'Veritabanı hatası oluştu.');
define('ERROR_UNAUTHORIZED', 'Yetkisiz erişim. Lütfen giriş yapınız.');
define('ERROR_INVALID_INPUT', 'Geçersiz giriş değeri.');

// Başarı mesajları
define('SUCCESS_CREATED', 'Başarıyla oluşturuldu.');
define('SUCCESS_UPDATED', 'Başarıyla güncellendi.');
define('SUCCESS_DELETED', 'Başarıyla silindi.');

// İş günleri
define('WORK_DAYS', ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma']);

// Unvanlar
define('UNVANLAR', [
    'Prof.' => 'Profesör',
    'Doç. Dr.' => 'Doçent Doktor',
    'Dr. Öğr. Üyesi' => 'Doktor Öğretim Üyesi',
    'Öğr. Gör.' => 'Öğretim Görevlisi',
    'Yrd.Doç.' => 'Yardımcı Doçent'
]);

// Sınıf Türleri
define('SINIF_TURLERI', [
    'SINIF' => 'Derslik',
    'LAB' => 'Laboratuvar',
    'AMFI' => 'Amfi',
    'SALON' => 'Salon',
    'STÜDYO' => 'Stüdyo'
]);

// Program Durumları
define('PROGRAM_DURUMLARI', [
    'TASLAK' => 'Taslak',
    'HAZIR' => 'Hazır',
    'UYGULAMADA' => 'Uygulamada',
    'BITTI' => 'Bitti'
]);

// Çakışma Türleri
define('CAKISMA_TURLERI', [
    'HOCA_CAKISMASI' => 'Hoca Çakışması',
    'SINIF_CAKISMASI' => 'Sınıf Çakışması',
    'PROGRAM_CAKISMASI' => 'Program Çakışması'
]);

// Önem Derecesi
define('ONEM_DERECESI', [
    'DÜŞÜK' => 'Düşük',
    'ORTA' => 'Orta',
    'YÜKSEK' => 'Yüksek',
    'KRİTİK' => 'Kritik'
]);

// Ders Tipleri
define('DERS_TIPLERI', [
    'DERS' => 'Ders',
    'SINAV' => 'Sınav',
    'UYGULAMA' => 'Uygulama'
]);

?>
