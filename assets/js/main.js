// Pasinler MYO Ders Programı - Ana JavaScript Dosyası

(function() {
    'use strict';
    
    // Sayfa hazır olduğunda
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Pasinler MYO Ders Programı Yönetim Sistemi Yüklendi');
        initializeApp();
    });
    
    function initializeApp() {
        // Theme ayarını kontrol et
        checkTheme();
        
        // Event listeners
        attachEventListeners();
    }
    
    function checkTheme() {
        const theme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', theme);
    }
    
    function attachEventListeners() {
        // Navbar aktif link güncelle
        const currentPage = window.location.pathname;
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            }
        });
    }
    
    // Utility fonksiyonları
    window.AppUtils = {
        // Tarih formatla
        formatDate: function(date) {
            const d = new Date(date);
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            const year = d.getFullYear();
            return `${day}/${month}/${year}`;
        },
        
        // Saati formatla
        formatTime: function(time) {
            return time.substring(0, 5);
        },
        
        // Hata mesajı göster
        showError: function(message) {
            alert('❌ Hata: ' + message);
        },
        
        // Başarı mesajı göster
        showSuccess: function(message) {
            alert('✅ ' + message);
        },
        
        // AJAX isteği yap
        ajax: function(url, method = 'GET', data = null) {
            return fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: data ? JSON.stringify(data) : null
            })
            .then(response => response.json())
            .catch(error => {
                console.error('AJAX Hatası:', error);
                throw error;
            });
        }
    };
    
})();
