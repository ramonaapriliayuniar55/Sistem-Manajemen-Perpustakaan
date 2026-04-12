<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Status Peminjaman Anggota</h1>

    <?php
    // Data anggota
    $nama_anggota = "Budi Santoso";
    $total_pinjaman = 2;
    $buku_terlambat = 1;
    $hari_keterlambatan = 5;

    // HITUNG DENDA
    $denda_per_hari = 1000;
    $total_denda = 0;

    if ($buku_terlambat > 0) {
        $total_denda = $buku_terlambat * $hari_keterlambatan * $denda_per_hari;

        // batas maksimal denda
        if ($total_denda > 50000) {
            $total_denda = 50000;
        }
    }

    // CEK STATUS PINJAM
    if ($buku_terlambat > 0) {
        $status = "Tidak bisa meminjam (ada keterlambatan)";
        $badge = "danger";
    } elseif ($total_pinjaman >= 3) {
        $status = "Tidak bisa meminjam (maksimal tercapai)";
        $badge = "warning";
    } else {
        $status = "Bisa meminjam buku";
        $badge = "success";
    }

    // LEVEL MEMBER (SWITCH)
    switch (true) {
        case ($total_pinjaman >= 0 && $total_pinjaman <= 5):
            $level = "Bronze";
            break;
        case ($total_pinjaman >= 6 && $total_pinjaman <= 15):
            $level = "Silver";
            break;
        default:
            $level = "Gold";
            break;
    }
    ?>

    <!-- Informasi Anggota -->
    <div class="card mb-4">
        <div class="card-body">
            <h5>Nama Anggota: <?= $nama_anggota ?></h5>
            <p>Total Peminjaman: <?= $total_pinjaman ?></p>
            <p>Level Member: <span class="badge bg-secondary"><?= $level ?></span></p>
        </div>
    </div>

    <!-- Status -->
    <div class="alert alert-<?= $badge ?>">
        <?= $status ?>
    </div>

    <!-- Detail -->
    <ul class="list-group mb-4">
        <li class="list-group-item">Buku Terlambat: <?= $buku_terlambat ?></li>
        <li class="list-group-item">Hari Keterlambatan: <?= $hari_keterlambatan ?> hari</li>
        <li class="list-group-item">
            Total Denda: <strong>Rp <?= number_format($total_denda, 0, ',', '.') ?></strong>
        </li>
    </ul>

    <?php if ($buku_terlambat > 0): ?>
        <div class="alert alert-warning">
            ⚠️ Anda memiliki keterlambatan. Harap segera mengembalikan buku dan membayar denda.
        </div>
    <?php endif; ?>

</div>
</body>
</html>