CREATE DATABASE IF NOT EXISTS perpustakaan;
USE perpustakaan;

CREATE TABLE IF NOT EXISTS books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(255) NOT NULL,
  penulis VARCHAR(255) NOT NULL,
  tahun INT NOT NULL,
  stok INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

DELETE FROM books;

INSERT INTO books (judul, penulis, tahun, stok) VALUES
('Laskar Pelangi', 'Andrea Hirata', 2005, 5),
('Bumi Manusia', 'Pramoedya Ananta Toer', 1980, 3);