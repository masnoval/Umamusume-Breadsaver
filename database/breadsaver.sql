CREATE DATABASE IF NOT EXISTS breadsaver;
USE breadsaver;

CREATE TABLE user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('pembeli','toko','admin') NOT NULL,
    no_hp VARCHAR(20)
);

CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    id_toko INT NOT NULL,
    nama_produk VARCHAR(100) NOT NULL,
    harga_asli DECIMAL(10,2) NOT NULL,
    harga_diskon DECIMAL(10,2) NOT NULL,
    stok INT NOT NULL,
    deskripsi TEXT,
    expired_at DATETIME,
    foto VARCHAR(255),
    CONSTRAINT fk_produk_toko
        FOREIGN KEY (id_toko) REFERENCES user(id_user)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE pesanan (
    id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    tanggal_pesan DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Menunggu Konfirmasi','Diproses','Selesai','Dibatalkan')
        DEFAULT 'Menunggu Konfirmasi',
    total_harga DECIMAL(10,2) DEFAULT 0,
    CONSTRAINT fk_pesanan_user
        FOREIGN KEY (id_user) REFERENCES user(id_user)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE detail_pesanan (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL,
    id_produk INT NOT NULL,
    jumlah INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_detail_pesanan
        FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detail_produk
        FOREIGN KEY (id_produk) REFERENCES produk(id_produk)
        ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE rating (
    id_rating INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL,
    bintang INT NOT NULL,
    ulasan TEXT,
    CONSTRAINT fk_rating_pesanan
        FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan)
        ON UPDATE CASCADE ON DELETE CASCADE
);
