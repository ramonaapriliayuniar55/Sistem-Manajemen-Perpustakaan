<?php
require_once __DIR__ . '/../../config/database.php';

$id = $_GET['id'] ?? 0;

// ambil data dulu (untuk foto)
$stmt = $conn->prepare("SELECT foto FROM anggota WHERE id_anggota=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit;
}

// hapus foto jika ada
if ($data['foto'] && file_exists("uploads/".$data['foto'])) {
    unlink("uploads/".$data['foto']);
}

// hapus data
$stmt = $conn->prepare("DELETE FROM anggota WHERE id_anggota=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: index.php?success=Data berhasil dihapus");
} else {
    header("Location: index.php?error=Gagal menghapus data");
}
exit;