<?php
/**
 * Çakışma Kontrol Algoritması Sınıfı
 * 
 * Hoca, Sınıf ve Program Bazlı Çakışmaları Kontrol Eder
 * AI Promptu: "Ders programı atamasında hoca aynı saate iki dersi alamaz,
 * sınıf aynı saatte iki dersi yapamaz, ve aynı sınıfın zorunlu dersleri
 * aynı saatte olamaz. Bu kontrolleri PHP'de implement et."
 */

class CakismaKontrol {
    private $db;
    private $cakismalar = [];
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Tüm çakışmaları kontrol et
     */
    public function tumCakismalariKontrolEt($atama_id) {
        $atama = $this->getAtamaDetay($atama_id);
        
        if (!$atama) {
            return ['hata' => 'Atama bulunamadı'];
        }
        
        // Her kontrol türünü çalıştır
        $this->hocaCakismasiniKontrolEt($atama);
        $this->sinifCakismasiniKontrolEt($atama);
        $this->programCakismasiniKontrolEt($atama);
        
        return $this->cakismalar;
    }
    
    /**
     * HOCA ÇAKIŞMASI: Bir hoca aynı gün ve aynı saat diliminde
     * iki farklı sınıfa atanamaz
     */
    private function hocaCakismasiniKontrolEt($atama) {
        $sql = "
            SELECT pa.id, d.ad as ders_adi, s.ad as sinif_adi
            FROM program_atamalari pa
            JOIN dersler d ON pa.ders_id = d.id
            JOIN siniflar s ON pa.sinif_id = s.id
            WHERE pa.gun = :gun
            AND d.hoca_id = :hoca_id
            AND pa.id != :atama_id
            AND pa.aktif = TRUE
            AND (
                (pa.baslama_saati < :bitis_saati AND pa.bitis_saati > :baslama_saati)
            )
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':gun' => $atama['gun'],
            ':hoca_id' => $atama['hoca_id'],
            ':atama_id' => $atama['id'],
            ':baslama_saati' => $atama['baslama_saati'],
            ':bitis_saati' => $atama['bitis_saati']
        ]);
        
        $cakisanlar = $stmt->fetchAll();
        
        if (!empty($cakisanlar)) {
            foreach ($cakisanlar as $cakisan) {
                $this->cakismalar[] = [
                    'tip' => 'HOCA_CAKISMASI',
                    'onem' => 'KRİTİK',
                    'mesaj' => "HOCA ÇAKIŞMASI: {$atama['hoca_adi']} ({$atama['unvan']}) 
                              {$atama['gun']} günü aynı saatte hem '{$atama['ders_adi']}' 
                              hem de '{$cakisan['ders_adi']}' derslerini veremez!",
                    'cakisan_atamasi_id' => $cakisan['id']
                ];
            }
        }
    }
    
    /**
     * SINIF ÇAKIŞMASI: Aynı sınıfta aynı saatte iki farklı ders yapılamaz
     */
    private function sinifCakismasiniKontrolEt($atama) {
        $sql = "
            SELECT pa.id, d.ad as ders_adi, h.ad_soyad as hoca_adi
            FROM program_atamalari pa
            JOIN dersler d ON pa.ders_id = d.id
            JOIN hocalar h ON d.hoca_id = h.id
            WHERE pa.gun = :gun
            AND pa.sinif_id = :sinif_id
            AND pa.id != :atama_id
            AND pa.aktif = TRUE
            AND (
                (pa.baslama_saati < :bitis_saati AND pa.bitis_saati > :baslama_saati)
            )
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':gun' => $atama['gun'],
            ':sinif_id' => $atama['sinif_id'],
            ':atama_id' => $atama['id'],
            ':baslama_saati' => $atama['baslama_saati'],
            ':bitis_saati' => $atama['bitis_saati']
        ]);
        
        $cakisanlar = $stmt->fetchAll();
        
        if (!empty($cakisanlar)) {
            foreach ($cakisanlar as $cakisan) {
                $this->cakismalar[] = [
                    'tip' => 'SINIF_CAKISMASI',
                    'onem' => 'KRİTİK',
                    'mesaj' => "SINIF ÇAKIŞMASI: '{$atama['sinif_adi']}' sınıfında 
                              {$atama['gun']} günü aynı saatte hem '{$atama['ders_adi']}' 
                              (Hoca: {$atama['hoca_adi']}) hem de '{$cakisan['ders_adi']}' 
                              (Hoca: {$cakisan['hoca_adi']}) dersleri yapılamaz!",
                    'cakisan_atamasi_id' => $cakisan['id']
                ];
            }
        }
    }
    
    /**
     * PROGRAM ÇAKIŞMASI: Aynı sınıfın (örneğin: Bilgisayar 1. Sınıf)
     * iki farklı zorunlu dersi aynı saatte konulamaz
     */
    private function programCakismasiniKontrolEt($atama) {
        // Ders zorunlu ise kontrol et
        $ders = $this->getDersDetay($atama['ders_id']);
        
        if (!$ders['zorunlu']) {
            return; // Seçmeli dersin çakışması önemli değildir
        }
        
        $sql = "
            SELECT pa.id, d.ad as ders_adi, h.ad_soyad as hoca_adi, d.zorunlu
            FROM program_atamalari pa
            JOIN dersler d ON pa.ders_id = d.id
            JOIN hocalar h ON d.hoca_id = h.id
            WHERE pa.gun = :gun
            AND pa.sinif_seviyesi_id = :sinif_seviyesi_id
            AND pa.id != :atama_id
            AND pa.aktif = TRUE
            AND d.zorunlu = TRUE
            AND (
                (pa.baslama_saati < :bitis_saati AND pa.bitis_saati > :baslama_saati)
            )
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':gun' => $atama['gun'],
            ':sinif_seviyesi_id' => $atama['sinif_seviyesi_id'],
            ':atama_id' => $atama['id'],
            ':baslama_saati' => $atama['baslama_saati'],
            ':bitis_saati' => $atama['bitis_saati']
        ]);
        
        $cakisanlar = $stmt->fetchAll();
        
        if (!empty($cakisanlar)) {
            foreach ($cakisanlar as $cakisan) {
                $this->cakismalar[] = [
                    'tip' => 'PROGRAM_CAKISMASI',
                    'onem' => 'YÜKSEK',
                    'mesaj' => "PROGRAM ÇAKIŞMASI: '{$atama['sinif_seviyesi_adi']}' 
                              sınıfının zorunlu iki dersi ({$atama['ders_adi']} ve 
                              {$cakisan['ders_adi']}) aynı saatte {$atama['gun']} günü 
                              olamaz! Öğrenciler her ikisine katılamayacaktır.",
                    'cakisan_atamasi_id' => $cakisan['id']
                ];
            }
        }
    }
    
    /**
     * Hoca saatini kontrol et (haftalık maksimum saat)
     */
    public function hocaSaatiKontrolEt($hoca_id, $yeni_saat = 0) {
        $sql = "
            SELECT SUM(d.haftalik_saat) as toplam_saat
            FROM dersler d
            WHERE d.hoca_id = :hoca_id
            AND d.aktif = TRUE
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':hoca_id' => $hoca_id]);
        $result = $stmt->fetch();
        
        $toplam_saat = (int)$result['toplam_saat'] + $yeni_saat;
        
        // Hoca maksimum 30 saat/hafta yapabilir
        if ($toplam_saat > 30) {
            return [
                'hata' => true,
                'mesaj' => "Hoca haftalık maksimum 30 saat ders yapabilir. 
                           Şu anki toplam: $toplam_saat saat"
            ];
        }
        
        return ['hata' => false];
    }
    
    /**
     * Atama detaylarını getir
     */
    private function getAtamaDetay($atama_id) {
        $sql = "
            SELECT 
                pa.id, pa.gun, pa.baslama_saati, pa.bitis_saati,
                d.id as ders_id, d.ad as ders_adi,
                h.id as hoca_id, h.ad_soyad as hoca_adi, h.unvan,
                s.id as sinif_id, s.ad as sinif_adi,
                ss.id as sinif_seviyesi_id, ss.ad as sinif_seviyesi_adi
            FROM program_atamalari pa
            JOIN dersler d ON pa.ders_id = d.id
            JOIN hocalar h ON d.hoca_id = h.id
            JOIN siniflar s ON pa.sinif_id = s.id
            JOIN sinif_seviyeleri ss ON pa.sinif_seviyesi_id = ss.id
            WHERE pa.id = :id
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $atama_id]);
        return $stmt->fetch();
    }
    
    /**
     * Ders detaylarını getir
     */
    private function getDersDetay($ders_id) {
        $sql = "SELECT * FROM dersler WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $ders_id]);
        return $stmt->fetch();
    }
    
    /**
     * Çakışmaları kaydet
     */
    public function cakismalariKaydet($atama_id, $cakismalar) {
        // Önce eski raporları sil
        $sql = "DELETE FROM cakisma_raporlari WHERE program_atamasi_id = :atama_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':atama_id' => $atama_id]);
        
        // Yeni raporları ekle
        foreach ($cakismalar as $cakisma) {
            $sql = "
                INSERT INTO cakisma_raporlari 
                (program_atamasi_id, cakisma_tipi, cakisan_atamasi_id, mesaj, onem_derecesi)
                VALUES (:atama_id, :tip, :cakisan_id, :mesaj, :onem)
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':atama_id' => $atama_id,
                ':tip' => $cakisma['tip'],
                ':cakisan_id' => $cakisma['cakisan_atamasi_id'] ?? null,
                ':mesaj' => $cakisma['mesaj'],
                ':onem' => $cakisma['onem']
            ]);
        }
    }
}

?>