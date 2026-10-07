CREATE DATABASE IF NOT EXISTS breadsaver
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE breadsaver;

-- Matikan pengecekan Foreign Key sementara agar penimpaan tabel lancar
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS rating;
DROP TABLE IF EXISTS detail_pesanan;
DROP TABLE IF EXISTS pesanan;
DROP TABLE IF EXISTS produk;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS memberi_nilai;
DROP TABLE IF EXISTS jual;
DROP TABLE IF EXISTS staff;
DROP TABLE IF EXISTS customer;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. TABEL USER (Pembeli, Toko, Admin)
CREATE TABLE user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('pembeli', 'toko', 'admin') NOT NULL,
    no_hp VARCHAR(20) NULL
) ENGINE=InnoDB;

-- 2. TABEL PRODUK
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
    CONSTRAINT fk_produk_toko FOREIGN KEY (id_toko)
        REFERENCES user(id_user)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CHECK (harga_asli >= 0),
    CHECK (harga_diskon >= 0 AND harga_diskon <= harga_asli),
    CHECK (stok >= 0)
) ENGINE=InnoDB;

-- 3. TABEL PESANAN
CREATE TABLE pesanan (
    id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    tanggal_pesan DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM(
        'Menunggu Konfirmasi',
        'Diproses',
        'Selesai',
        'Dibatalkan'
    ) NOT NULL DEFAULT 'Menunggu Konfirmasi',
    total_harga DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_pesanan_user FOREIGN KEY (id_user)
        REFERENCES user(id_user)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CHECK (total_harga >= 0)
) ENGINE=InnoDB;

-- 4. TABEL DETAIL PESANAN
CREATE TABLE detail_pesanan (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL,
    id_produk INT NOT NULL,
    jumlah INT NOT NULL DEFAULT 1,
    subtotal DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_detail_pesanan FOREIGN KEY (id_pesanan)
        REFERENCES pesanan(id_pesanan)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_detail_produk FOREIGN KEY (id_produk)
        REFERENCES produk(id_produk)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CHECK (jumlah > 0),
    CHECK (subtotal >= 0)
) ENGINE=InnoDB;

-- 5. TABEL RATING
CREATE TABLE rating (
    id_rating INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL UNIQUE,
    bintang INT NOT NULL,
    ulasan TEXT NULL,
    CONSTRAINT fk_rating_pesanan FOREIGN KEY (id_pesanan)
        REFERENCES pesanan(id_pesanan)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CHECK (bintang BETWEEN 1 AND 5)
) ENGINE=InnoDB;

-- INSERT DATA ADMIN AWAL
INSERT INTO user (nama, email, password, role, no_hp)
VALUES (
    'Admin BreadSaver',
    'admin@breadsaver.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC7j7uG5sQ1J1K3W6w.',
    'admin',
    '081234567890'
);

-- CEK HASIL PENGERJAAN
SHOW TABLES;

DESCRIBE user;
DESCRIBE produk;
DESCRIBE pesanan;
DESCRIBE detail_pesanan;
DESCRIBE rating;
