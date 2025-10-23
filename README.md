# Tabela Hesapla WordPress Plugin

WordPress eklentisi olarak geliştirilmiş profesyonel tabela fiyat hesaplama sistemi.

## Özellikler

- ✅ Gerçek zamanlı AJAX tabanlı fiyat hesaplama
- ✅ Özel post type ile teklif yönetimi
- ✅ Admin paneli ayarlar sayfası
- ✅ E-posta bildirimleri
- ✅ Responsive tasarım
- ✅ Güvenli WordPress kodlama standartları
- ✅ Shortcode desteği
- ✅ Temiz ve dokümante edilmiş kod

## Kurulum

1. Plugin dosyalarını `/wp-content/plugins/tabela-hesapla/` dizinine yükleyin
2. WordPress admin panelinden "Eklentiler" menüsüne gidin
3. "Tabela Hesapla" eklentisini aktifleştirin
4. "Tabela Hesaplama" menüsünden fiyatları ayarlayın

## Kullanım

### Shortcode Kullanımı

Herhangi bir sayfaya veya yazıya aşağıdaki shortcode'u ekleyin:

```
[tabela_hesapla]
```

### Admin Ayarları

1. WordPress admin panelinde "Tabela Hesaplama" menüsüne tıklayın
2. Tabela tiplerinin m² fiyatlarını girin:
   - Pleksi Tabela
   - Krom Tabela
   - Kutu Harf
3. Işıklı tabela çarpanını ayarlayın (örn: 0.25 = %25 ek maliyet)
4. Montaj ücretini belirleyin
5. Değişiklikleri kaydedin

### Teklifleri Görüntüleme

1. "Tabela Hesaplama" > "Teklifler" menüsüne gidin
2. Tüm gelen teklifleri listeden görüntüleyin
3. Detayları görmek için bir teklife tıklayın

## Özellikler

### Frontend Hesaplayıcı

- Tabela tipi seçimi (Pleksi, Krom, Kutu Harf)
- Boyut girişi (genişlik x yükseklik, cm cinsinden)
- Işıklı tabela seçeneği
- Montaj dahil seçeneği
- Gerçek zamanlı fiyat hesaplama
- İletişim formu

### AJAX İşlemleri

- Anlık fiyat hesaplama
- Form gönderimi
- Nonce doğrulama
- Hata yönetimi

### Güvenlik

- ABSPATH kontrolü
- Nonce doğrulama
- Sanitizasyon ve escaping
- Yetki kontrolü
- XSS koruması
- CSRF koruması

### E-posta Bildirimleri

- Admin bildirimi
- Müşteri onay e-postası
- Teklif detayları
- Müşteri bilgileri

## Dosya Yapısı

```
tabela-hesapla/
├── tabela-hesapla.php          # Ana eklenti dosyası
├── includes/
│   ├── admin-settings.php      # Admin ayarları ve meta box
│   ├── calculator.php          # Fiyat hesaplama sınıfı
│   └── ajax-handler.php        # AJAX işleyicileri
├── templates/
│   └── frontend.php            # Frontend şablonu
├── assets/
│   ├── css/
│   │   └── style.css           # Stil dosyası
│   └── js/
│       └── script.js           # JavaScript dosyası
└── README.md                   # Dokümantasyon
```

## Geliştirici Notları

### Sınıflar ve Fonksiyonlar

#### VC_Tabela_Calculator

Fiyat hesaplama işlemlerini yöneten ana sınıf.

**Metotlar:**
- `calculate($data)` - Fiyat hesaplama
- `get_types()` - Mevcut tabela tiplerini getir

#### AJAX İşleyicileri

- `vc_tabela_ajax_calculate()` - Fiyat hesaplama
- `vc_tabela_ajax_submit()` - Form gönderimi
- `vc_tabela_send_notification()` - E-posta bildirimi

### Filtreler ve Hooklar

Eklenti aşağıdaki WordPress hookları kullanır:

- `register_activation_hook` - Aktivasyon
- `register_deactivation_hook` - Deaktivasyon
- `init` - Post type kaydı
- `wp_enqueue_scripts` - Asset yükleme
- `admin_menu` - Admin menü
- `admin_init` - Ayarları kaydet
- `add_meta_boxes` - Meta box ekle
- `wp_ajax_*` - AJAX işleyicileri

### Özelleştirme

#### Yeni Tabela Tipi Ekleme

1. Admin ayarlarına yeni alan ekleyin
2. `VC_Tabela_Calculator::get_types()` metodunu güncelleyin
3. Varsayılan fiyatı `vc_tabela_activate()` fonksiyonuna ekleyin

#### Stil Özelleştirme

CSS dosyasını (`assets/css/style.css`) düzenleyerek görünümü özelleştirebilirsiniz.

## Teknik Detaylar

### Gereksinimler

- WordPress 5.0 veya üzeri
- PHP 7.0 veya üzeri
- MySQL 5.6 veya üzeri

### Veritabanı

Eklenti aşağıdaki verileri saklar:

**wp_options:**
- `vc_tabela_options` - Eklenti ayarları

**wp_posts:**
- Post type: `vc_offer` - Teklifler

**wp_postmeta:**
- `_vc_customer_name` - Müşteri adı
- `_vc_customer_email` - E-posta
- `_vc_customer_phone` - Telefon
- `_vc_width` - Genişlik
- `_vc_height` - Yükseklik
- `_vc_type` - Tabela tipi
- `_vc_has_light` - Işıklı seçeneği
- `_vc_has_installation` - Montaj seçeneği
- `_vc_total` - Toplam fiyat
- `_vc_message` - Müşteri mesajı

## Destek

Sorunlar veya sorular için GitHub Issues kullanın.

## Lisans

Bu proje MIT lisansı altında lisanslanmıştır.

## Yazar

Yusuf Kaan Çelik - [GitHub](https://github.com/yusufkaancelik)

## Katkıda Bulunma

1. Fork edin
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Değişikliklerinizi commit edin (`git commit -m 'Add some amazing feature'`)
4. Branch'i push edin (`git push origin feature/amazing-feature`)
5. Pull Request oluşturun
