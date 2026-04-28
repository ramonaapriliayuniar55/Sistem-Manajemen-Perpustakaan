/* ========================================= */
/*        CREATE DATABASE                    */
/* ========================================= */
CREATE DATABASE perpustakaan;
USE perpustakaan;

/* ========================================= */
/*        TABLE kategori_buku                */
/* ========================================= */
CREATE TABLE kategori_buku (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL UNIQUE,
    deskripsi TEXT,
    is_deleted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/* ========================================= */
/*        TABLE penerbit                     */
/* ========================================= */
CREATE TABLE penerbit (
    id_penerbit INT AUTO_INCREMENT PRIMARY KEY,
    nama_penerbit VARCHAR(100) NOT NULL,
    alamat TEXT,
    telepon VARCHAR(15),
    email VARCHAR(100),
    is_deleted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/* ========================================= */
/*        TABLE rak (BONUS)                  */
/* ========================================= */
CREATE TABLE rak (
    id_rak INT AUTO_INCREMENT PRIMARY KEY,
    nama_rak VARCHAR(50),
    lokasi VARCHAR(100),
    is_deleted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/* ========================================= */
/*        TABLE buku                         */
/* ========================================= */
CREATE TABLE buku (
    id_buku INT AUTO_INCREMENT PRIMARY KEY,
    kode_buku VARCHAR(10),
    judul VARCHAR(255),
    pengarang VARCHAR(100),
    tahun_terbit INT,
    isbn VARCHAR(50),
    harga INT,
    stok INT,
    deskripsi TEXT,
    id_kategori INT,
    id_penerbit INT,
    id_rak INT,
    is_deleted TINYINT(1) DEFAULT 0,
    deleted_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_kategori) REFERENCES kategori_buku(id_kategori),
    FOREIGN KEY (id_penerbit) REFERENCES penerbit(id_penerbit),
    FOREIGN KEY (id_rak) REFERENCES rak(id_rak)
);

/* ========================================= */
/*        INSERT DATA                        */
/* ========================================= */

INSERT INTO kategori_buku (nama_kategori, deskripsi) VALUES
('Programming','Buku pemrograman'),
('Database','Buku basis data'),
('Web Design','Desain web'),
('Artificial Intelligence','AI & ML'),
('Jaringan','Jaringan komputer');

INSERT INTO penerbit (nama_penerbit, alamat, telepon, email) VALUES
('Informatika','Bandung','0811','info@informatika.com'),
('Graha Ilmu','Yogyakarta','0822','info@graha.com'),
('Andi','Yogyakarta','0833','info@andi.com'),
('Elex Media','Jakarta','0844','info@elex.com'),
('Gramedia','Jakarta','0855','info@gramedia.com');

INSERT INTO rak (nama_rak, lokasi) VALUES
('Rak A','Lantai 1'),
('Rak B','Lantai 1'),
('Rak C','Lantai 2'),
('Rak D','Lantai 2'),
('Rak E','Lantai 3');

INSERT INTO buku 
(kode_buku, judul, pengarang, tahun_terbit, isbn, harga, stok, deskripsi, id_kategori, id_penerbit, id_rak)
VALUES
('BK-001','Pemrograman PHP untuk Pemula','Budi Raharjo',2023,'978-01',100000,10,'PHP dasar',1,1,1),
('BK-002','Mastering MySQL Database','Andi',2022,'978-02',110000,5,'MySQL',2,2,1),
('BK-003','Laravel Advanced','Siti',2024,'978-03',140000,7,'Laravel',1,1,1),
('BK-004','Web Design Principles','Dedi',2023,'978-04',90000,8,'Desain web',3,3,2),
('BK-005','PHP Web Services','Budi',2024,'978-05',105000,6,'API PHP',1,1,2),
('BK-006','PostgreSQL Advanced','Ahmad',2024,'978-06',120000,5,'PostgreSQL',2,2,2),
('BK-007','JavaScript Modern','Siti',2023,'978-07',95000,9,'JS',1,1,3),
('BK-008','React Native','Ahmad',2024,'978-08',150000,6,'Mobile',1,1,3),
('BK-009','Python Programming','Rusman',2023,'978-09',95000,6,'Python',1,1,3),
('BK-010','Data Science','Budi',2024,'978-10',120000,5,'Data science',4,5,4),
('BK-011','Deep Learning','Sari',2024,'978-11',140000,3,'Deep learning',4,5,4),
('BK-012','Keamanan Jaringan','Ahmad',2023,'978-12',110000,4,'Security',5,2,4),
('BK-013','UI UX Design','Dian',2023,'978-13',90000,7,'UI UX',3,4,5),
('BK-014','Frontend React','Lisa',2024,'978-14',105000,6,'Frontend',3,4,5),
('BK-015','Machine Learning','Bayu',2024,'978-15',150000,4,'ML',4,5,5);

/* ========================================= */
/*        QUERY JOIN (WAJIB)                 */
/* ========================================= */

-- 1. JOIN utama
SELECT b.judul, k.nama_kategori, p.nama_penerbit
FROM buku b
JOIN kategori_buku k ON b.id_kategori = k.id_kategori
JOIN penerbit p ON b.id_penerbit = p.id_penerbit
WHERE b.is_deleted = 0;

-- 2. Jumlah buku per kategori
SELECT k.nama_kategori, COUNT(*) AS jumlah_buku
FROM buku b
JOIN kategori_buku k ON b.id_kategori = k.id_kategori
WHERE b.is_deleted = 0
GROUP BY k.nama_kategori;

-- 3. Jumlah buku per penerbit
SELECT p.nama_penerbit, COUNT(*) AS jumlah_buku
FROM buku b
JOIN penerbit p ON b.id_penerbit = p.id_penerbit
WHERE b.is_deleted = 0
GROUP BY p.nama_penerbit;

-- 4. Detail lengkap buku
SELECT b.*, k.nama_kategori, p.nama_penerbit, r.nama_rak
FROM buku b
JOIN kategori_buku k ON b.id_kategori = k.id_kategori
JOIN penerbit p ON b.id_penerbit = p.id_penerbit
JOIN rak r ON b.id_rak = r.id_rak
WHERE b.is_deleted = 0;

/* ========================================= */
/*        STORED PROCEDURE (2)               */
/* ========================================= */

DELIMITER //

-- Procedure 1: tampilkan semua buku
CREATE PROCEDURE tampilkan_buku()
BEGIN
    SELECT b.judul, k.nama_kategori, p.nama_penerbit
    FROM buku b
    JOIN kategori_buku k ON b.id_kategori = k.id_kategori
    JOIN penerbit p ON b.id_penerbit = p.id_penerbit
    WHERE b.is_deleted = 0;
END //

-- Procedure 2: cari buku berdasarkan kategori (PUNYA PARAMETER)
CREATE PROCEDURE cari_buku_kategori(IN nama_kat VARCHAR(50))
BEGIN
    SELECT b.judul, k.nama_kategori
    FROM buku b
    JOIN kategori_buku k ON b.id_kategori = k.id_kategori
    WHERE k.nama_kategori = nama_kat
    AND b.is_deleted = 0;
END //

DELIMITER ;

-- tampilkan semua buku
CALL tampilkan_buku();

-- cari buku kategori tertentu
CALL cari_buku_kategori('Programming');