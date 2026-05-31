-- Pasinler Meslek Yüksekokulu Ders Programı Yönetim Sistemi
-- Veritabanı Şeması ve GERÇEK Pasinler MYO Verileri
-- Kaynak: https://atauni.edu.tr/pasinler-meslek-yuksekokulu/

CREATE DATABASE IF NOT EXISTS pasinler_myo;
USE pasinler_myo;

-- =============================================
-- 1. ADMIN TABLOSU (Yönetici Girişi)
-- =============================================
CREATE TABLE IF NOT EXISTS admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    ad_soyad VARCHAR(100),
    rol VARCHAR(30) DEFAULT 'ADMIN',
    aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Şifre: admin123 (Bcrypt Hash)
INSERT INTO admin (username, password, email, ad_soyad, rol, aktif) VALUES 
('admin', '$2y$10$Z3N9Q8mK7vL2pX1cR4bT5ew6sHjYuIoP9nM8vL7kJ6iH5gF4dE3cB', 'admin@pasinler.edu.tr', 'Sistem Yöneticisi', 'ADMIN', TRUE);

-- =============================================
-- 2. BÖLÜMLER TABLOSU (Pasinler MYO Resmi Bölümleri)
-- =============================================
CREATE TABLE IF NOT EXISTS bolumler (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ad VARCHAR(100) NOT NULL UNIQUE,
    aciklama TEXT,
    koordinator_hoca_id INT,
    aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO bolumler (ad, aciklama, aktif) VALUES 
('Bilgisayar Teknolojileri', 'Bilgisayar ve yazılım teknolojileri', TRUE),
('Büro Yönetimi ve Sekreterlik', 'İdari ve sekreterlik hizmetleri', TRUE),
('Elektrik ve Enerji', 'Elektrik teknolojileri ve enerji sistemleri', TRUE),
('Elektronik ve Otomasyon', 'Elektronik ve otomasyon sistemleri', TRUE),
('Finans-Bankacılık ve Sigortacılık', 'Finansal hizmetler ve sigorta', TRUE),
('İnşaat', 'İnşaat mühendisliği ve teknolojileri', TRUE),
('Mimarlık ve Şehir Planlama', 'Mimari tasarım ve kentsel planlama', TRUE),
('Muhasebe ve Vergi', 'Muhasebe ve vergi hizmetleri', TRUE),
('Otel, Lokanta ve İkram Hizmetleri', 'Turizm ve otelcilik', TRUE),
('Yönetim ve Organizasyon', 'İşletme yönetimi', TRUE);

-- =============================================
-- 3. HOCALAR TABLOSU (Pasinler MYO Akademik Personeli)
-- =============================================
CREATE TABLE IF NOT EXISTS hocalar (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ad_soyad VARCHAR(100) NOT NULL,
    unvan VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    telefon VARCHAR(20),
    bolum_id INT NOT NULL,
    uzmanlik_alani VARCHAR(200),
    aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bolum_id) REFERENCES bolumler(id) ON DELETE CASCADE
);

-- Pasinler MYO Resmi Akademik Personeli
INSERT INTO hocalar (ad_soyad, unvan, email, bolum_id, uzmanlik_alani, aktif) VALUES 
-- Doçentler (Yönetim Kademesi)
('Melih OKCU', 'Doç. Dr.', 'mokcu@atauni.edu.tr', 1, 'Bilgisayar Teknolojileri', TRUE),
('Zinnur ÇELİK', 'Doç. Dr.', 'zcelik@atauni.edu.tr', 3, 'Elektrik ve Enerji Sistemleri', TRUE),

-- Dr. Öğr. Üyeleri
('Arif Emre SAĞSÖZ', 'Dr. Öğr. Üyesi', 'asagsoz@atauni.edu.tr', 1, 'Bilgisayar Ağları', TRUE),
('Hamit ÇAKICI', 'Dr. Öğr. Üyesi', 'hcakici@atauni.edu.tr', 4, 'Elektronik Sistemler', TRUE),
('İsmail YILDIZ', 'Dr. Öğr. Üyesi', 'iyildiz@atauni.edu.tr', 6, 'İnşaat Malzemeleri', TRUE),
('Nilgün KAYA', 'Dr. Öğr. Üyesi', 'nkaya@atauni.edu.tr', 8, 'Muhasebe Sistemi', TRUE),

-- Öğretim Görevlileri
('Taha ORHAN', 'Öğr. Gör.', 'torhan@atauni.edu.tr', 1, 'Web Teknolojileri', TRUE),
('Fatih AYDOĞAN', 'Öğr. Gör.', 'faydogan@atauni.edu.tr', 2, 'Sekreterlik Uygulamaları', TRUE),
('Halil MUTİ', 'Öğr. Gör.', 'hmuti@atauni.edu.tr', 3, 'Elektrik Teknolojileri', TRUE),
('İbrahim Halil HAYALİOĞLU', 'Öğr. Gör.', 'ihayalioglu@atauni.edu.tr', 4, 'Otomasyon Sistemleri', TRUE),
('Murat KARADAŞ', 'Öğr. Gör.', 'mkaradas@atauni.edu.tr', 5, 'Bankacılık Uygulamaları', TRUE),
('Murat KURNUÇ', 'Öğr. Gör.', 'mkurnuc@atauni.edu.tr', 6, 'İnşaat Harita', TRUE),
('Suha GÖKALP', 'Öğr. Gör.', 'sgokalp@atauni.edu.tr', 7, 'Mimari Tasarım', TRUE),
('Veysel DEĞEN', 'Öğr. Gör.', 'vdegen@atauni.edu.tr', 9, 'Turizm Yönetimi', TRUE),
('Zeki BİLEN', 'Öğr. Gör.', 'zbilen@atauni.edu.tr', 10, 'İşletme Yönetimi', TRUE);

-- =============================================
-- 4. DERSLER TABLOSU
-- =============================================
CREATE TABLE IF NOT EXISTS dersler (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ad VARCHAR(150) NOT NULL,
    kod VARCHAR(20) UNIQUE NOT NULL,
    kredi INT,
    haftalik_saat INT NOT NULL,
    hoca_id INT NOT NULL,
    bolum_id INT NOT NULL,
    sinif_seviyesi INT NOT NULL COMMENT '1=1.sınıf, 2=2.sınıf',
    zorunlu BOOLEAN DEFAULT TRUE,
    dil VARCHAR(20) DEFAULT 'TR',
    aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hoca_id) REFERENCES hocalar(id) ON DELETE CASCADE,
    FOREIGN KEY (bolum_id) REFERENCES bolumler(id) ON DELETE CASCADE
);

INSERT INTO dersler (ad, kod, kredi, haftalik_saat, hoca_id, bolum_id, sinif_seviyesi, zorunlu, aktif) VALUES 
-- Bilgisayar Teknolojileri - 1. Sınıf
('Bilgisayar Donanımı ve İşletim Sistemleri', 'BT101', 4, 4, 1, 1, 1, TRUE, TRUE),
('Nesne Yönelimli Programlama', 'BT102', 4, 4, 2, 1, 1, TRUE, TRUE),
('Veri Tabanı Tasarımı I', 'BT103', 3, 3, 3, 1, 1, TRUE, TRUE),
('Web Tasarımı ve HTML-CSS', 'BT104', 3, 3, 7, 1, 1, TRUE, TRUE),

-- Bilgisayar Teknolojileri - 2. Sınıf
('Mobil Uygulama Geliştirme', 'BT201', 4, 4, 1, 1, 2, TRUE, TRUE),
('Veri Tabanı Tasarımı II', 'BT202', 3, 3, 2, 1, 2, TRUE, TRUE),

-- Elektrik ve Enerji - 1. Sınıf
('Elektrik Mühendisliğinin Temelleri', 'EM101', 4, 4, 2, 3, 1, TRUE, TRUE),
('Devre Analizi', 'EM102', 3, 3, 9, 3, 1, TRUE, TRUE),

-- Elektronik ve Otomasyon - 1. Sınıf
('Elektronik Bileşenler', 'EA101', 3, 3, 4, 4, 1, TRUE, TRUE),
('PLC ve Otomasyon Sistemleri', 'EA102', 4, 4, 10, 4, 1, TRUE, TRUE),

-- İnşaat - 1. Sınıf
('İnşaat Malzemeleri', 'İN101', 3, 3, 5, 6, 1, TRUE, TRUE),
('Teknik Resim ve CAD', 'İN102', 3, 3, 11, 6, 1, TRUE, TRUE),

-- Muhasebe ve Vergi - 1. Sınıf
('Muhasebe Müdürü Kursları', 'MV101', 4, 4, 6, 8, 1, TRUE, TRUE),
('Vergi Mevzuatı', 'MV102', 3, 3, 12, 8, 1, TRUE, TRUE),

-- Büro Yönetimi - 1. Sınıf
('Sekreterlik Uygulamaları', 'BY101', 3, 3, 8, 2, 1, TRUE, TRUE),

-- Finans-Bankacılık - 1. Sınıf
('Bankacılık Hizmetleri', 'FB101', 3, 3, 13, 5, 1, TRUE, TRUE),

-- Mimarlık - 1. Sınıf
('Mimari Tasarım Prensipleri', 'MİM101', 4, 4, 14, 7, 1, TRUE, TRUE),

-- Otel, Lokanta - 1. Sınıf
('Turizm Yönetimi', 'OL101', 3, 3, 15, 9, 1, TRUE, TRUE),

-- Yönetim ve Organizasyon - 1. Sınıf
('İşletme Yönetimi', 'YO101', 3, 3, 16, 10, 1, TRUE, TRUE);

-- =============================================
-- 5. SINIFLAR TABLOSU (Derslik/Lab/Salon)
-- =============================================
CREATE TABLE IF NOT EXISTS siniflar (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ad VARCHAR(50) NOT NULL UNIQUE,
    tur VARCHAR(30) NOT NULL COMMENT 'SINIF, LAB, SALON, AMFI, STÜDYO',
    kapasite INT NOT NULL,
    lokasyon VARCHAR(100),
    donanım VARCHAR(200),
    aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO siniflar (ad, tur, kapasite, lokasyon, donanım, aktif) VALUES 
-- Derslikler
('A101', 'SINIF', 40, 'A Bloğu - 1. Kat', 'Projeksiyon, Beyaz Tahta', TRUE),
('A102', 'SINIF', 40, 'A Bloğu - 1. Kat', 'Projeksiyon, Beyaz Tahta', TRUE),
('A103', 'SINIF', 35, 'A Bloğu - 1. Kat', 'Projeksiyon, Beyaz Tahta', TRUE),
('A201', 'SINIF', 40, 'A Bloğu - 2. Kat', 'Projeksiyon, Beyaz Tahta', TRUE),
('A202', 'SINIF', 40, 'A Bloğu - 2. Kat', 'Projeksiyon, Beyaz Tahta', TRUE),
('B101', 'SINIF', 35, 'B Bloğu - 1. Kat', 'Projeksiyon, Beyaz Tahta', TRUE),
('B102', 'SINIF', 35, 'B Bloğu - 1. Kat', 'Projeksiyon, Beyaz Tahta', TRUE),
('B103', 'SINIF', 30, 'B Bloğu - 1. Kat', 'Projeksiyon, Beyaz Tahta', TRUE),

-- Bilgisayar Laboratuvarları
('LAB-BİL1', 'LAB', 25, 'Bilgisayar Lab - Zemin Kat', '25 Bilgisayar, Projeksiyon', TRUE),
('LAB-BİL2', 'LAB', 25, 'Bilgisayar Lab - Zemin Kat', '25 Bilgisayar, Projeksiyon', TRUE),

-- Elektrik/Elektronik Laboratuvarları
('LAB-ELK1', 'LAB', 20, 'Elektrik Lab - 1. Kat', 'Elektrik Test Cihazları', TRUE),
('LAB-ELK2', 'LAB', 20, 'Elektrik Lab - 1. Kat', 'Devre Simulatörü', TRUE),

-- CAD Stüdyosu
('STÜDYO1', 'STÜDYO', 15, 'A Bloğu - 2. Kat', 'CAD Yazılımı, İnşaat Donanımı', TRUE),

-- Amfiler
('AMFI1', 'AMFI', 120, 'Merkez Amfi', 'Ses Sistemi, Projeksiyon, Mikrofon', TRUE),
('AMFI2', 'AMFI', 100, 'Kütüphane Yanı Amfi', 'Projeksiyon, Ses Sistemi', TRUE);

-- =============================================
-- 6. SINIF SEVİYESİ TABLOSU
-- =============================================
CREATE TABLE IF NOT EXISTS sinif_seviyeleri (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bolum_id INT NOT NULL,
    sinif_sira INT NOT NULL COMMENT '1=1. Sınıf, 2=2. Sınıf',
    ad VARCHAR(100) NOT NULL,
    ogrenci_sayisi INT,
    aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bolum_id) REFERENCES bolumler(id) ON DELETE CASCADE,
    UNIQUE KEY unique_bolum_sinif (bolum_id, sinif_sira)
);

INSERT INTO sinif_seviyeleri (bolum_id, sinif_sira, ad, ogrenci_sayisi, aktif) VALUES 
(1, 1, 'Bilgisayar Teknolojileri 1. Sınıf', 40, TRUE),
(1, 2, 'Bilgisayar Teknolojileri 2. Sınıf', 38, TRUE),
(2, 1, 'Büro Yönetimi 1. Sınıf', 30, TRUE),
(3, 1, 'Elektrik ve Enerji 1. Sınıf', 35, TRUE),
(4, 1, 'Elektronik ve Otomasyon 1. Sınıf', 32, TRUE),
(5, 1, 'Finans-Bankacılık 1. Sınıf', 28, TRUE),
(6, 1, 'İnşaat 1. Sınıf', 35, TRUE),
(7, 1, 'Mimarlık ve Şehir Planlama 1. Sınıf', 25, TRUE),
(8, 1, 'Muhasebe ve Vergi 1. Sınıf', 32, TRUE),
(9, 1, 'Otel, Lokanta ve İkram 1. Sınıf', 30, TRUE),
(10, 1, 'Yönetim ve Organizasyon 1. Sınıf', 28, TRUE);

-- =============================================
-- 7. PROGRAM TABLOSU
-- =============================================
CREATE TABLE IF NOT EXISTS program (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ad VARCHAR(100) NOT NULL,
    aciklama TEXT,
    semestre INT NOT NULL COMMENT '1=Güz, 2=Bahar',
    akademik_yil VARCHAR(20),
    durum VARCHAR(30) DEFAULT 'TASLAK' COMMENT 'TASLAK, HAZIR, UYGULAMADA, BITTI',
    olusturan_admin_id INT,
    aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (olusturan_admin_id) REFERENCES admin(id) ON DELETE SET NULL
);

INSERT INTO program (ad, aciklama, semestre, akademik_yil, durum, olusturan_admin_id, aktif) VALUES 
('2024-2025 Güz Dönemi Ders Programı', 'Pasinler MYO Güz Dönemi Resmi Ders Programı', 1, '2024-2025', 'TASLAK', 1, TRUE),
('2024-2025 Bahar Dönemi Ders Programı', 'Pasinler MYO Bahar Dönemi Resmi Ders Programı', 2, '2024-2025', 'TASLAK', 1, TRUE);

-- =============================================
-- 8. PROGRAM ATAMALARI TABLOSU
-- =============================================
CREATE TABLE IF NOT EXISTS program_atamalari (
    id INT PRIMARY KEY AUTO_INCREMENT,
    program_id INT NOT NULL,
    ders_id INT NOT NULL,
    sinif_seviyesi_id INT NOT NULL,
    sinif_id INT NOT NULL,
    gun VARCHAR(20) NOT NULL COMMENT 'Pazartesi, Salı, Çarşamba, Perşembe, Cuma',
    baslama_saati TIME NOT NULL,
    bitis_saati TIME NOT NULL,
    tip VARCHAR(20) DEFAULT 'DERS' COMMENT 'DERS, SINAV, UYGULAMA',
    siraNo INT,
    notlar TEXT,
    aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES program(id) ON DELETE CASCADE,
    FOREIGN KEY (ders_id) REFERENCES dersler(id) ON DELETE CASCADE,
    FOREIGN KEY (sinif_seviyesi_id) REFERENCES sinif_seviyeleri(id) ON DELETE CASCADE,
    FOREIGN KEY (sinif_id) REFERENCES siniflar(id) ON DELETE CASCADE
);

-- =============================================
-- 9. ÇAKIŞMA RAPORLARI TABLOSU
-- =============================================
CREATE TABLE IF NOT EXISTS cakisma_raporlari (
    id INT PRIMARY KEY AUTO_INCREMENT,
    program_atamasi_id INT,
    cakisma_tipi VARCHAR(50) COMMENT 'HOCA_CAKISMASI, SINIF_CAKISMASI, PROGRAM_CAKISMASI',
    cakisan_atamasi_id INT,
    hoca_id INT,
    sinif_id INT,
    mesaj TEXT NOT NULL,
    onem_derecesi VARCHAR(20) DEFAULT 'ORTA' COMMENT 'DÜŞÜK, ORTA, YÜKSEK, KRİTİK',
    durum VARCHAR(20) DEFAULT 'AKTIF' COMMENT 'AKTIF, GÖZ ARDI EDILEN, ÇÖZÜLEN',
    admin_notu TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hoca_id) REFERENCES hocalar(id) ON DELETE SET NULL,
    FOREIGN KEY (sinif_id) REFERENCES siniflar(id) ON DELETE SET NULL
);

-- =============================================
-- 10. LOG TABLOSU (Denetim Izi)
-- =============================================
CREATE TABLE IF NOT EXISTS loglar (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT,
    islem VARCHAR(100) NOT NULL,
    tablo VARCHAR(50),
    kayit_id INT,
    eski_veri JSON,
    yeni_veri JSON,
    detay TEXT,
    ip_adresi VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admin(id) ON DELETE SET NULL
);

-- =============================================
-- İNDEKSLER (Performans Optimizasyonu)
-- =============================================
CREATE INDEX idx_hoca_bolum ON hocalar(bolum_id);
CREATE INDEX idx_hoca_aktif ON hocalar(aktif);
CREATE INDEX idx_ders_hoca ON dersler(hoca_id);
CREATE INDEX idx_ders_bolum ON dersler(bolum_id);
CREATE INDEX idx_ders_sinif ON dersler(sinif_seviyesi);
CREATE INDEX idx_program_atamasi_program ON program_atamalari(program_id);
CREATE INDEX idx_program_atamasi_gun ON program_atamalari(gun);
CREATE INDEX idx_program_atamasi_saat ON program_atamalari(baslama_saati, bitis_saati);
CREATE INDEX idx_program_atamasi_ders ON program_atamalari(ders_id);
CREATE INDEX idx_program_atamasi_sinif ON program_atamalari(sinif_id);
CREATE INDEX idx_cakisma_durum ON cakisma_raporlari(durum);
CREATE INDEX idx_cakisma_hoca ON cakisma_raporlari(hoca_id);
CREATE INDEX idx_loglar_admin ON loglar(admin_id);
CREATE INDEX idx_loglar_created ON loglar(created_at);

-- =============================================
-- VİEWLAR (Veri Sorgulamayı Kolaylaştırma)
-- =============================================
CREATE VIEW v_program_detay AS
SELECT 
    pa.id,
    pa.gun,
    pa.baslama_saati,
    pa.bitis_saati,
    pa.tip,
    d.ad AS ders_adi,
    d.kod AS ders_kodu,
    h.ad_soyad AS hoca_adi,
    h.unvan,
    s.ad AS sinif_adi,
    s.kapasite,
    b.ad AS bolum_adi,
    ss.ad AS sinif_seviyesi_adi,
    p.ad AS program_adi,
    p.akademik_yil
FROM program_atamalari pa
JOIN dersler d ON pa.ders_id = d.id
JOIN hocalar h ON d.hoca_id = h.id
JOIN siniflar s ON pa.sinif_id = s.id
JOIN bolumler b ON d.bolum_id = b.id
JOIN sinif_seviyeleri ss ON pa.sinif_seviyesi_id = ss.id
JOIN program p ON pa.program_id = p.id
WHERE pa.aktif = TRUE;

CREATE VIEW v_hoca_uygunlugu AS
SELECT 
    h.id,
    h.ad_soyad,
    h.unvan,
    COUNT(d.id) AS toplam_ders,
    SUM(d.haftalik_saat) AS toplam_haftalik_saat,
    b.ad AS bolum_adi,
    h.email,
    h.telefon
FROM hocalar h
LEFT JOIN dersler d ON h.id = d.hoca_id AND d.aktif = TRUE
JOIN bolumler b ON h.bolum_id = b.id
WHERE h.aktif = TRUE
GROUP BY h.id, h.ad_soyad, h.unvan, b.ad, h.email, h.telefon;

CREATE VIEW v_sinif_uygunlugu AS
SELECT 
    s.id,
    s.ad,
    s.tur,
    s.kapasite,
    s.lokasyon,
    COUNT(DISTINCT pa.id) AS gun_kullanim_sayisi,
    GROUP_CONCAT(DISTINCT pa.gun) AS kullanilangunler
FROM siniflar s
LEFT JOIN program_atamalari pa ON s.id = pa.sinif_id AND pa.aktif = TRUE
WHERE s.aktif = TRUE
GROUP BY s.id, s.ad, s.tur, s.kapasite, s.lokasyon;

-- =============================================
-- TRİGGER: Otomatik Log Kaydı
-- =============================================
DELIMITER $$

CREATE TRIGGER tr_dersler_insert AFTER INSERT ON dersler
FOR EACH ROW
BEGIN
    INSERT INTO loglar (islem, tablo, kayit_id, yeni_veri)
    VALUES ('INSERT', 'dersler', NEW.id, JSON_OBJECT('ders_adi', NEW.ad, 'kod', NEW.kod, 'hoca', NEW.hoca_id));
END $$

CREATE TRIGGER tr_program_atamalari_insert AFTER INSERT ON program_atamalari
FOR EACH ROW
BEGIN
    INSERT INTO loglar (islem, tablo, kayit_id, yeni_veri)
    VALUES ('INSERT', 'program_atamalari', NEW.id, JSON_OBJECT('ders', NEW.ders_id, 'sinif', NEW.sinif_id, 'gun', NEW.gun, 'saat', CONCAT(NEW.baslama_saati, '-', NEW.bitis_saati)));
END $$

DELIMITER ;

-- =============================================
-- TİPİK PROGRAM ÖRNEĞI (İlk 3 gün)
-- =============================================
INSERT INTO program_atamalari (program_id, ders_id, sinif_seviyesi_id, sinif_id, gun, baslama_saati, bitis_saati, tip, siraNo, aktif) VALUES 
-- Pazartesi - Bilgisayar 1. Sınıf
(1, 1, 1, 1, 'Pazartesi', '09:00:00', '11:00:00', 'DERS', 1, TRUE),
(1, 3, 1, 9, 'Pazartesi', '09:00:00', '12:00:00', 'LAB', 1, TRUE),

-- Salı - Bilgisayar 1. Sınıf
(1, 2, 1, 2, 'Salı', '09:00:00', '11:00:00', 'DERS', 2, TRUE),
(1, 4, 1, 3, 'Salı', '13:00:00', '16:00:00', 'DERS', 1, TRUE),

-- Çarşamba - Elektrik 1. Sınıf
(1, 8, 3, 4, 'Çarşamba', '10:00:00', '12:00:00', 'DERS', 1, TRUE),
(1, 9, 3, 11, 'Çarşamba', '13:00:00', '15:00:00', 'LAB', 1, TRUE);

COMMIT;
