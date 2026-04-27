# Tugas 1 – Eksplorasi Database Perpustakaan

## Identitas

* Nama : Ramona Aprilia Yuniar
* NIM  : 60324039
* Mata Kuliah : Pemrograman Web II


## Deskripsi

Repository ini berisi file query SQL (`query_tugas.sql`) yang digunakan untuk
melakukan eksplorasi database perpustakaan, meliputi:

* Statistik buku
* Filter dan pencarian
* Grouping dan agregasi
* Update data
* Laporan khusus

Setiap query dijalankan menggunakan phpMyAdmin dan hasilnya
didokumentasikan dalam bentuk screenshot.

## 1. STATISTIK BUKU

### 1. Total Buku Seluruhnya

Menghitung total seluruh stok buku.

```sql id="q1"
SELECT SUM(stok) AS total_buku FROM buku;
```

![Query 1](images/query1.png)

---

### 2. Total Nilai Inventaris

Menghitung total nilai buku (harga × stok).

```sql id="q2"
SELECT SUM(harga * stok) AS total_nilai_inventaris FROM buku;
```

![Query 2](images/query2.png)

---

### 3. Rata-rata Harga Buku

```sql id="q3"
SELECT AVG(harga) AS rata_rata_harga FROM buku;
```

![Query 3](images/query3.png)

---

### 4. Buku Termahal

Menampilkan buku dengan harga tertinggi.

```sql id="q4"
SELECT judul, harga FROM buku ORDER BY harga DESC LIMIT 1;
```

![Query 4](images/query4.png)

---

### 5. Buku dengan Stok Terbanyak

```sql id="q5"
SELECT judul, stok FROM buku ORDER BY stok DESC LIMIT 1;
```

![Query 5](images/query5.png)

---

## 2. FILTER DAN PENCARIAN

### 6. Buku kategori Programming dengan harga < 100.000

```sql id="q6"
SELECT * FROM buku 
WHERE kategori = 'Programming' AND harga < 100000;
```

![Query 6](images/query6.png)

---

### 7. Buku dengan judul mengandung "PHP" atau "MySQL"

```sql id="q7"
SELECT * FROM buku 
WHERE judul LIKE '%PHP%' OR judul LIKE '%MySQL%';
```

![Query 7](images/query7.png)

---

### 8. Buku yang terbit tahun 2024

```sql id="q8"
SELECT * FROM buku 
WHERE tahun_terbit = 2024;
```

![Query 8](images/query8.png)

---

### 9. Buku dengan stok antara 5 sampai 10

```sql id="q9"
SELECT * FROM buku 
WHERE stok BETWEEN 5 AND 10;
```

![Query 9](images/query9.png)

---

### 10. Buku dengan pengarang "Budi Raharjo"

```sql id="q10"
SELECT * FROM buku 
WHERE pengarang = 'Budi Raharjo';
```

![Query 10](images/query10.png)

---

## 3. GROUPING & AGREGASI

### 11. Jumlah buku dan total stok per kategori

```sql id="q11"
SELECT kategori,
       COUNT(*) AS jumlah_judul,
       SUM(stok) AS total_stok
FROM buku
GROUP BY kategori;
```

![Query 11](images/query11.png)

---

### 12. Rata-rata harga per kategori

```sql id="q12"
SELECT kategori,
       AVG(harga) AS rata_rata_harga
FROM buku
GROUP BY kategori;
```

![Query 12](images/query12.png)

---

### 13. Kategori dengan total nilai inventaris terbesar

```sql id="q13"
SELECT kategori,
       SUM(harga * stok) AS total_nilai
FROM buku
GROUP BY kategori
ORDER BY total_nilai DESC
LIMIT 1;
```

![Query 13](images/query13.png)

---

## 4. UPDATE DATA

### 14. Naikkan harga buku kategori Programming sebesar 5%

```sql id="q14"
UPDATE buku
SET harga = harga * 1.05
WHERE kategori = 'Programming';
```

![Query 14](images/query14.png)

---

### 15. Tambah stok 10 untuk buku dengan stok < 5

```sql id="q15"
UPDATE buku
SET stok = stok + 10
WHERE stok < 5;
```

![Query 15](images/query15&16.png)

---

## 5. LAPORAN KHUSUS

### 16. Buku yang perlu restocking (stok < 5)

```sql id="q16"
SELECT * FROM buku WHERE stok < 5;
```

![Query 16](images/query15&16.png)

---

### 17. Top 5 buku termahal

```sql id="q17"
SELECT judul, harga FROM buku ORDER BY harga DESC LIMIT 5;
```

![Query 17](images/query17.png)



# Tugas 2 – Eksplorasi Database Perpustakaan

## Deskripsi

Repository ini berisi implementasi desain database perpustakaan menggunakan MySQL dengan relasi antar tabel, query JOIN, stored procedure, dan ERD.

---

## Struktur Tabel

### Struktur Buku
![Struktur Buku](images/struktur_buku.png)

### Struktur Kategori Buku
![Struktur Kategori](images/struktur_kategori_buku.png)

### Struktur Penerbit
![Struktur Penerbit](images/strukturpenerbit.png)

## Data Tabel

### Data Buku
![Data Buku](images/isi_data_buku.jpeg)

### Data Kategori Buku
![Data Kategori](images/isi_data_kategori_buku.jpeg)

### Data Penerbit
![Data Penerbit](images/isi_tabel_penerbit.png)

## Hasil JOIN

### JOIN Buku
![Join Buku](images/join_buku.png)

### JOIN Kategori
![Join Kategori](images/join_kategori.png)

### JOIN Penerbit
![Join Penerbit](images/join_penerbit.png)

### JOIN Rak
![Join Rak](images/join_rak.png)

## Stored Procedure

### Hasil Procedure Buku Programming
![Procedure 1](images/hasil_call_procedur_bukuprograming.jpeg)

### Hasil Procedure Tampilkan Semua Buku
![Procedure 2](images/hasil_call_prosedur_tampilkansemuabuku.jpeg)

---
## ERD

![ERD](images/erd.jpeg)
