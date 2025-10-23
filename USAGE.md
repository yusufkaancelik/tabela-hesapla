# Tabela Hesapla - Kullanım Kılavuzu

## Hızlı Başlangıç

### 1. Kurulum
1. Plugin dosyalarını `/wp-content/plugins/tabela-hesapla/` dizinine yükleyin
2. WordPress admin panelinden eklentiyi aktifleştirin
3. "Tabela Hesaplama" menüsünden ayarları yapılandırın

### 2. Ayarları Yapılandırma

**Tabela Hesaplama** > **Ayarlar** sayfasından:

- **Pleksi Tabela (m² fiyatı)**: 500 TL (varsayılan)
- **Krom Tabela (m² fiyatı)**: 750 TL (varsayılan)
- **Kutu Harf (m² fiyatı)**: 1000 TL (varsayılan)
- **Işıklı Tabela Çarpanı**: 0.25 (varsayılan - %25 ek maliyet anlamına gelir)
- **Montaj Ücreti**: 250 TL (varsayılan)

### 3. Sayfaya Eklemek

Herhangi bir sayfa veya yazıya şu shortcode'u ekleyin:

```
[tabela_hesapla]
```

### 4. Müşteri Kullanımı

Müşteriler aşağıdaki bilgileri girecektir:

1. **Tabela Tipi**: Pleksi, Krom veya Kutu Harf
2. **Genişlik (cm)**: Örn: 100
3. **Yükseklik (cm)**: Örn: 50
4. **Işıklı Tabela**: Checkbox (opsiyonel)
5. **Montaj Dahil**: Checkbox (opsiyonel)

### 5. Fiyat Hesaplama

- Değerler girildiğinde fiyat **otomatik olarak hesaplanır**
- Alan (m²) gösterilir
- Ara toplam, ışık maliyeti, montaj ücreti ayrı ayrı gösterilir
- **Toplam fiyat** vurgulanarak gösterilir

### 6. Teklif Gönderme

Hesaplama tamamlandıktan sonra müşteri bilgileri:

- **Ad Soyad** (zorunlu)
- **E-posta** (zorunlu)
- **Telefon** (opsiyonel)
- **Mesaj** (opsiyonel)

Formun gönderilmesiyle:
- Teklif veritabanına kaydedilir
- Site yöneticisine e-posta gönderilir
- Müşteriye onay e-postası gönderilir

### 7. Teklifleri Görüntüleme

**Tabela Hesaplama** > **Teklifler** menüsünden:

- Tüm teklifler listelenir
- Bir teklife tıklayarak detayları görüntüleyebilirsiniz
- Müşteri bilgileri ve hesaplama detayları meta box'ta gösterilir

## Hesaplama Formülü

```
Alan = (Genişlik / 100) × (Yükseklik / 100) m²
Ara Toplam = Alan × Birim Fiyat
Işık Maliyeti = Ara Toplam × Işık Çarpanı (eğer seçiliyse)
Toplam = Ara Toplam + Işık Maliyeti + Montaj Ücreti
```

## Örnek Hesaplama

**Girişler:**
- Tabela Tipi: Pleksi Tabela (500 TL/m²)
- Genişlik: 100 cm
- Yükseklik: 50 cm
- Işıklı: Evet
- Montaj: Evet

**Hesaplama:**
- Alan: (100/100) × (50/100) = 0.5 m²
- Ara Toplam: 0.5 × 500 = 250 TL
- Işık Maliyeti: 250 × 0.25 = 62.50 TL
- Montaj Ücreti: 250 TL
- **Toplam: 562.50 TL**

## E-posta Şablonları

### Admin E-postası
Konu: "Yeni Tabela Teklifi - [Müşteri Adı]"

İçeriği:
- Müşteri bilgileri (ad, e-posta, telefon)
- Teklif detayları (tip, boyutlar, seçenekler)
- Toplam fiyat
- Müşteri mesajı
- Admin panelinde görüntüleme linki

### Müşteri E-postası
Konu: "Tabela Teklif Talebiniz Alındı"

İçeriği:
- Kişiselleştirilmiş mesaj
- Teklif özeti
- Tahmini fiyat
- Dönüş süresi bilgisi

## Özelleştirme İpuçları

### Stil Özelleştirme
`assets/css/style.css` dosyasını düzenleyerek renkleri ve stilleri değiştirebilirsiniz:

```css
.vc-tabela-submit {
    background: #0073aa; /* Buton rengi */
}

.vc-tabela-result-total .value {
    color: #0073aa; /* Toplam fiyat rengi */
}
```

### Yeni Tabela Tipi Ekleme
1. `includes/calculator.php` dosyasında `get_types()` metoduna yeni tip ekleyin
2. `includes/admin-settings.php` dosyasında yeni alan ekleyin
3. `tabela-hesapla.php` dosyasında varsayılan fiyat ekleyin

## Sorun Giderme

### Hesaplama Çalışmıyor
- Tarayıcı konsolunda JavaScript hataları kontrol edin
- jQuery'nin yüklendiğinden emin olun
- AJAX isteklerinin doğru URL'e gittiğini kontrol edin

### E-posta Gönderilmiyor
- WordPress e-posta ayarlarını kontrol edin
- SMTP eklentisi kullanmayı düşünün
- Spam klasörünü kontrol edin

### Teklifler Kayıt Olmuyor
- Veritabanı bağlantısını kontrol edin
- WordPress debug modunu açıp hataları görüntüleyin
- Dosya izinlerini kontrol edin

## Destek

Sorunlar veya sorular için:
- GitHub Issues: https://github.com/yusufkaancelik/tabela-hesapla/issues
- E-posta desteği (gerekirse)

## Güncellemeler

Plugin güncellemelerini takip etmek için GitHub deposunu yıldızlayın ve izleyin.
