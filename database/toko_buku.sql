CREATE DATABASE IF NOT EXISTS toko_buku;
USE toko_buku;

CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255),
    role ENUM('admin','user')
);

CREATE TABLE buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(100),
    penulis VARCHAR(100),
    harga INT,
    stok INT,
    gambar VARCHAR(100) NULL
);

CREATE TABLE harga (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buku_id INT,
    harga_awal INT,
    harga_akhir INT,
    tanggal_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buku_id) REFERENCES buku(id)
);

CREATE TABLE pembayaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    buku_id INT,
    total_harga INT,
    metode_pembayaran VARCHAR(50),
    tanggal_pembayaran TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (buku_id) REFERENCES buku(id)
);