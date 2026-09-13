-- ============================================
-- Database: undangan_pernikahan (v2 - Multi-Invitation)
-- ============================================

CREATE DATABASE IF NOT EXISTS undangan_pernikahan
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE undangan_pernikahan;

-- 1. Tabel Master Undangan
CREATE TABLE IF NOT EXISTS invitations (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug       VARCHAR(100) NOT NULL UNIQUE,
  title      VARCHAR(255) NOT NULL,
  created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Tabel Admin
CREATE TABLE IF NOT EXISTS admins (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(50)  NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tabel Settings (Dihubungkan ke Undangan)
CREATE TABLE IF NOT EXISTS settings (
  invitation_id INT UNSIGNED NOT NULL,
  key_name      VARCHAR(100) NOT NULL,
  key_value     TEXT         NOT NULL,
  PRIMARY KEY (invitation_id, key_name),
  FOREIGN KEY (invitation_id) REFERENCES invitations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tabel RSVP (Dihubungkan ke Undangan)
CREATE TABLE IF NOT EXISTS rsvp (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  invitation_id INT UNSIGNED NOT NULL,
  nama          VARCHAR(150) NOT NULL,
  jumlah_tamu   TINYINT UNSIGNED NOT NULL DEFAULT 1,
  status        ENUM('hadir','tidak') NOT NULL,
  alasan        TEXT         NULL,
  created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (invitation_id) REFERENCES invitations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Tabel Ucapan & Doa (Dihubungkan ke Undangan)
CREATE TABLE IF NOT EXISTS ucapan (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  invitation_id INT UNSIGNED NOT NULL,
  nama          VARCHAR(150) NOT NULL,
  pesan         TEXT         NOT NULL,
  created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (invitation_id) REFERENCES invitations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Tabel Daftar Tamu (Personalized Links)
CREATE TABLE IF NOT EXISTS guests (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  invitation_id INT UNSIGNED NOT NULL,
  nama          VARCHAR(150) NOT NULL,
  no_hp         VARCHAR(20) NULL,
  slug          VARCHAR(150) NOT NULL,
  FOREIGN KEY (invitation_id) REFERENCES invitations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default Data
INSERT IGNORE INTO admins (username, password) 
VALUES ('admin', '$2y$10$QDYfPWq8qa2orL8EzuDekuaNsWXWotMYvaEkXc5.qPQuAeS1iVRHW'); 

-- Contoh Undangan Pertama
INSERT IGNORE INTO invitations (id, slug, title) VALUES (1, 'elvy-rokim', 'Elvy & Rokim Wedding');

INSERT IGNORE INTO settings (invitation_id, key_name, key_value) VALUES
(1, 'groom_name', 'Mukamat Abdul Rokim'),
(1, 'bride_name', 'Elvy Nur Fauziyah'),
(1, 'wedding_date', '2026-06-09'),
(1, 'wedding_time_start', '08:00'),
(1, 'wedding_time_end', '10:00'),
(1, 'wedding_location', 'Kediaman Mempelai Wanita'),
(1, 'wedding_map_link', ''),
(1, 'reception_date', '2026-06-11'),
(1, 'reception_time_start', '11:00'),
(1, 'reception_time_end', 'Selesai'),
(1, 'reception_location', 'Kediaman Mempelai Wanita'),
(1, 'reception_map_link', ''),
(1, 'gift_bank', 'BANK BRI'),
(1, 'gift_account', '00550 11527 30502'),
(1, 'gift_owner', 'Elvy Nur Fauziyah'),
(1, 'gift_address', 'Ds. Pagerwojo Dsn. Pagerwojo Kec. Perak Kab. Jombang RT/RW. 05/04'),
(1, 'gift_bank_logo', ''),
(1, 'music_volume', '50'),
(1, 'music_autoplay', '1');

-- 7. Tabel Galeri Foto
CREATE TABLE IF NOT EXISTS gallery (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  invitation_id INT UNSIGNED NOT NULL,
  image_path    VARCHAR(255) NOT NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (invitation_id) REFERENCES invitations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Tabel Musik/Lagu
CREATE TABLE IF NOT EXISTS music (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  invitation_id INT UNSIGNED NOT NULL,
  file_name     VARCHAR(255) NOT NULL,
  file_path     VARCHAR(255) NOT NULL,
  file_size     INT UNSIGNED NOT NULL,
  is_active     TINYINT(1) NOT NULL DEFAULT 0,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (invitation_id) REFERENCES invitations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Tabel Kisah Kasih (Stories)
CREATE TABLE IF NOT EXISTS stories (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  invitation_id INT UNSIGNED NOT NULL,
  tahun         VARCHAR(50)  NOT NULL,
  judul         VARCHAR(255) NOT NULL,
  isi           TEXT         NOT NULL,
  created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (invitation_id) REFERENCES invitations(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default Kisah Kasih untuk Contoh Undangan Pertama
INSERT IGNORE INTO stories (id, invitation_id, tahun, judul, isi) VALUES
(1, 1, '2025', 'Pertemuan Pertama', 'Kami bermula dari sebuah DM sederhana di Instagram. Pesan singkat yang tak disangka menjadi awal perjalanan dua hati yang saling menemukan. Dari obrolan ringan setiap hari, kami belajar saling mengenal, memahami, hingga tumbuh rasa nyaman yang perlahan berubah menjadi cinta'),
(2, 1, '2026', 'Janji Suci', 'Mengukir janji untuk saling mendukung dalam suka dan duka, melangkah bersama menuju masa depan yang cerah.'),
(3, 1, 'diamond', 'Hari Kemenangan', 'Menyatukan dua keluarga besar dalam ikatan suci pernikahan yang langgeng, selamanya.');

