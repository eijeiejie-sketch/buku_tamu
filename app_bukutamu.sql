-- =============================================
-- Database: app_bukutamu
-- Aplikasi Buku Tamu Sederhana (PHP Native + SB Admin 2)
-- =============================================

CREATE DATABASE IF NOT EXISTS app_bukutamu;
USE app_bukutamu;

-- Tabel buku_tamu
CREATE TABLE IF NOT EXISTS buku_tamu (
    id_tamu       VARCHAR(5) NOT NULL,
    tanggal       DATE NOT NULL,
    nama_tamu     VARCHAR(255) NOT NULL,
    alamat        TEXT NOT NULL,
    no_hp         VARCHAR(13) NOT NULL,
    bertemu       VARCHAR(255) NOT NULL,
    kepentingan   VARCHAR(255) NOT NULL,
    gambar        VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (id_tamu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel users
CREATE TABLE IF NOT EXISTS users (
    id_user     VARCHAR(5) NOT NULL,
    username    VARCHAR(255) NOT NULL,
    password    VARCHAR(255) NOT NULL,
    user_role   ENUM('admin', 'operator') NOT NULL,
    PRIMARY KEY (id_user)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data awal user (password sudah di-hash dengan password_hash(), plain text: "admin123" & "operator123")
INSERT INTO users (id_user, username, password, user_role) VALUES
('usr01', 'admin',    '$2y$10$vDQ4faXPfyjmLgwpZs5hIe6qabtIz2nHjnR/HjOk4iK43h7bXWL22', 'admin'),
('usr02', 'operator', '$2y$10$jfNajbLmG.l3mrWa2kXjS.x9R9Bc5XHkXSW6s/5T6u1vR8J/xYLvm', 'operator');

-- Contoh data tamu (opsional)
INSERT INTO buku_tamu (id_tamu, tanggal, nama_tamu, alamat, no_hp, bertemu, kepentingan, gambar) VALUES
('zt001', CURDATE(), 'Aria', 'Jl. Suroso No. 51 Cianjur', '0896786556781', 'BKK', 'Mengambil Sertifikat PKL', NULL),
('zt002', CURDATE(), 'Lucia', 'Jl. Mimpi Terus No. 90', '0897684123178', 'Bu Astri', 'Mengambil Sertifikat UJIKOM', NULL);
