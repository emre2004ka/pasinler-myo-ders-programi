<?php
/**
 * CRUD İşlemleri Temel Sınıfı
 */

class CRUD {
    protected $db;
    protected $tablo;
    
    public function __construct($db, $tablo) {
        $this->db = $db;
        $this->tablo = $tablo;
    }
    
    /**
     * Tümünü Getir
     */
    public function tumunuGetir() {
        try {
            $sql = "SELECT * FROM {$this->tablo} WHERE aktif = TRUE ORDER BY created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return ['hata' => $e->getMessage()];
        }
    }
    
    /**
     * ID ile Getir
     */
    public function idileGetir($id) {
        try {
            $sql = "SELECT * FROM {$this->tablo} WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            return ['hata' => $e->getMessage()];
        }
    }
    
    /**
     * Kayıt Ekle
     */
    public function ekle($veri) {
        try {
            $kolonlar = array_keys($veri);
            $placeholders = array_map(fn($k) => '?', $kolonlar);
            
            $sql = "INSERT INTO {$this->tablo} (" . implode(',', $kolonlar) . ") 
                    VALUES (" . implode(',', $placeholders) . ")";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(array_values($veri));
            
            return [
                'basarili' => true,
                'id' => $this->db->lastInsertId(),
                'mesaj' => SUCCESS_CREATED
            ];
        } catch (Exception $e) {
            return [
                'basarili' => false,
                'hata' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Kayıt Güncelle
     */
    public function guncelle($id, $veri) {
        try {
            $setler = [];
            foreach ($veri as $kolon => $deger) {
                $setler[] = "$kolon = ?";
            }
            
            $sql = "UPDATE {$this->tablo} SET " . implode(', ', $setler) . " WHERE id = ?";
            
            $values = array_values($veri);
            $values[] = $id;
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($values);
            
            return [
                'basarili' => true,
                'mesaj' => SUCCESS_UPDATED
            ];
        } catch (Exception $e) {
            return [
                'basarili' => false,
                'hata' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Kayıt Sil (Soft Delete)
     */
    public function sil($id) {
        try {
            $sql = "UPDATE {$this->tablo} SET aktif = FALSE WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            return [
                'basarili' => true,
                'mesaj' => SUCCESS_DELETED
            ];
        } catch (Exception $e) {
            return [
                'basarili' => false,
                'hata' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Kayıt Ara (Arama)
     */
    public function ara($aramaKolonu, $aramaMetni) {
        try {
            $sql = "SELECT * FROM {$this->tablo} 
                    WHERE {$aramaKolonu} LIKE ? AND aktif = TRUE 
                    ORDER BY created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(["%$aramaMetni%"]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return ['hata' => $e->getMessage()];
        }
    }
    
    /**
     * Belirli Kolona Göre Filtrele
     */
    public function filtrele($kolon, $deger) {
        try {
            $sql = "SELECT * FROM {$this->tablo} 
                    WHERE {$kolon} = ? AND aktif = TRUE 
                    ORDER BY created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$deger]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return ['hata' => $e->getMessage()];
        }
    }
    
    /**
     * Toplam Sayı
     */
    public function toplamSayi() {
        try {
            $sql = "SELECT COUNT(*) as toplam FROM {$this->tablo} WHERE aktif = TRUE";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['toplam'];
        } catch (Exception $e) {
            return 0;
        }
    }
}

?>