# Hothifa_onlineGunluk
PHP + MySQL ile yapılmış basit bir günlük uygulaması

# Hothifa_onlineGunluk

PHP + MySQL ile hazırlanmış basit bir online günlük uygulaması.

---

## 🎯 Canlı Demo

https://hothifa.wuaze.com

---

## 🚀 Özellikler

- Kullanıcı Kayıt & Giriş (şifre hash’li)  
- Günlük Girdisi Oluşturma, Listeleme, Düzenleme, Silme (CRUD)  
- Girdileri ⭐ Favorilere Ekleme/Çıkarma  
- Modern, temiz CSS tasarım  

---

## 🛠️ Kullanılan Teknolojiler

- **Frontend:** HTML5, CSS3  
- **Backend:** PHP 8+, PDO  
- **Veritabanı:** MySQL  
- **Sürüm Kontrol:** Git, GitHub  
- **Yayınlama:** InfinityFree  

---

## 📁 Proje Yapısı

/htdocs
├── index.php
├── register.php
├── login.php
├── logout.php
├── entries.php
├── entry_create.php
├── entry_edit.php
├── favorites.php
├── test-db.php
├── backend/
│ ├── db.php
│ ├── user/
│ └── entry/
└── css/
└── style.css
/sql
└── init.sql

## ⚙️ Kurulum (Development)

1. Repo’yu klonlayın:  
   ```bash
   git clone https://github.com/aliha21/Hothifa_onlineGunluk.git
   cd Hothifa_onlineGunluk
   ```
2. `sql/init.sql`’i kendi MySQL’inize import edin.  
3. `public_html/backend/db.php` içindeki bağlantı bilgilerini güncelleyin.  
4. Dosyaları `/htdocs` altına yükleyin (XAMPP veya InfinityFree).  
5. `http://localhost/entries.php` veya `https://hothifa.wuaze.com` adresini açıp test edin.

---

## 📄 Lisans

MIT © 2025 Hothifa Fawaz Abobakr ALI
