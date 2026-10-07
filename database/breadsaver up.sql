CREATE DATABASE IF NOT EXISTS breadsaver 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE breadsaver;

-- Matikan pengecekan Foreign Key untuk pembersihan tabel & view lama
SET FOREIGN_KEY_CHECKS = 0;
DROP VIEW IF EXISTS memberi_nilai;
DROP VIEW IF EXISTS jual;
DROP VIEW IF EXISTS customer;
DROP VIEW IF EXISTS staff;
DROP TABLE IF EXISTS rating;
DROP TABLE IF EXISTS detail_pesanan;
DROP TABLE IF EXISTS pesanan;
DROP TABLE IF EXISTS produk;
DROP TABLE IF EXISTS USER;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. TABEL USER
CREATE TABLE USER (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    PASSWORD VARCHAR(255) NOT NULL,
    ROLE ENUM('pembeli', 'toko', 'admin') NOT NULL,
    no_hp VARCHAR(20) NULL
) ENGINE=INNODB;

-- 2. TABEL PRODUK (Termasuk Kolom Kompatibilitas Kode PHP)
CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    id_toko INT NOT NULL,
    nama_produk VARCHAR(100) NOT NULL,
    harga_asli DECIMAL(10,2) NOT NULL,
    harga_diskon DECIMAL(10,2) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    deskripsi TEXT NULL,
    expired_at DATETIME NOT NULL,
    foto VARCHAR(255) NULL,
    
    -- Kolom Virtual Kompatibilitas untuk Aplikasi PHP
    id_user INT GENERATED ALWAYS AS (id_toko) VIRTUAL,
    kadaluarsa DATETIME GENERATED ALWAYS AS (expired_at) VIRTUAL,
    STATUS ENUM('Aktif', 'Habis', 'Nonaktif') GENERATED ALWAYS AS (IF(stok > 0, 'Aktif', 'Habis')) VIRTUAL,
    kategori VARCHAR(100) DEFAULT 'Roti',

    CONSTRAINT fk_produk_toko FOREIGN KEY (id_toko) 
        REFERENCES USER(id_user) ON UPDATE CASCADE ON DELETE RESTRICT,
    CHECK (harga_asli >= 0),
    CHECK (harga_diskon >= 0 AND harga_diskon <= harga_asli),
    CHECK (stok >= 0)
) ENGINE=INNODB;

-- 3. TABEL PESANAN
CREATE TABLE pesanan (
    id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    tanggal_pesan DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    STATUS ENUM('Menunggu Konfirmasi', 'Diproses', 'Selesai', 'Dibatalkan') NOT NULL DEFAULT 'Menunggu Konfirmasi',
    total_harga DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_pesanan_user FOREIGN KEY (id_user) 
        REFERENCES USER(id_user) ON UPDATE CASCADE ON DELETE RESTRICT,
    CHECK (total_harga >= 0)
) ENGINE=INNODB;

-- 4. TABEL DETAIL PESANAN
CREATE TABLE detail_pesanan (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL,
    id_produk INT NOT NULL,
    jumlah INT NOT NULL DEFAULT 1,
    subtotal DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_detail_pesanan FOREIGN KEY (id_pesanan) 
        REFERENCES pesanan(id_pesanan) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detail_produk FOREIGN KEY (id_produk) 
        REFERENCES produk(id_produk) ON UPDATE CASCADE ON DELETE RESTRICT,
    CHECK (jumlah > 0),
    CHECK (subtotal >= 0)
) ENGINE=INNODB;

-- 5. TABEL RATING
CREATE TABLE rating (
    id_rating INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL UNIQUE,
    bintang INT NOT NULL,
    ulasan TEXT NULL,
    CONSTRAINT fk_rating_pesanan FOREIGN KEY (id_pesanan) 
        REFERENCES pesanan(id_pesanan) ON UPDATE CASCADE ON DELETE CASCADE,
    CHECK (bintang BETWEEN 1 AND 5)
) ENGINE=INNODB;

-- =============================================
-- VIEW KOMPATIBILITAS UNTUK TABEL LAMA PHP
-- =============================================

CREATE VIEW staff AS 
SELECT id_user, nama, email, PASSWORD, no_hp, 'Yogyakarta' AS alamat 
FROM USER WHERE ROLE IN ('admin', 'toko');

CREATE VIEW customer AS 
SELECT id_user AS id_customer, nama, email, PASSWORD, no_hp, 'Yogyakarta' AS alamat 
FROM USER WHERE ROLE = 'pembeli';

CREATE VIEW jual AS 
SELECT 
    p.id_pesanan,
    p.id_user AS id_customer,
    dp.id_produk,
    CONCAT('ORD-', p.id_pesanan) AS kode_pesanan,
    p.total_harga,
    p.tanggal_pesan AS tanggal,
    p.status,
    dp.subtotal / dp.jumlah AS harga_satuan,
    dp.jumlah
FROM pesanan p
JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan;

CREATE VIEW memberi_nilai AS 
SELECT 
    r.id_rating AS id_nilai,
    p.id_user AS id_customer,
    dp.id_produk,
    r.id_pesanan,
    r.bintang,
    r.ulasan,
    p.tanggal_pesan AS tanggal
FROM rating r
JOIN pesanan p ON r.id_pesanan = p.id_pesanan
JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan;

-- =============================================
-- DATA SAMPEL AWAL
-- =============================================

INSERT INTO USER (nama, email, PASSWORD, ROLE, no_hp) VALUES
('Admin BreadSaver', 'admin@breadsaver.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC7j7uG5sQ1J1K3W6w.', 'admin', '081234567890'),
('Bakery Wonder', 'toko.wonder@breadsaver.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC7j7uG5sQ1J1K3W6w.', 'toko', '0812987654321'),
('Adventa Deno', 'pembeli.adventa@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC7j7uG5sQ1J1K3W6w.', 'pembeli', '085712345678');

INSERT INTO produk (id_toko, nama_produk, harga_asli, harga_diskon, stok, deskripsi, expired_at) VALUES
(2, 'Roti Cokelat Keju', 15000.00, 8000.00, 10, 'Roti isi cokelat lumer dan keju parut', DATE_ADD(NOW(), INTERVAL 2 DAY)),
(2, 'Croissant Butter', 22000.00, 12000.00, 5, 'Croissant renyah dengan butter asli', DATE_ADD(NOW(), INTERVAL 1 DAY));