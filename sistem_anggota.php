<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Anggota Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
<?php
require_once __DIR__ . '/functions_anggota.php';

// Data anggota
$anggota_list = [
    ["id"=>"AGT-001","nama"=>"Budi Santoso","email"=>"budi@email.com","telepon"=>"08123","alamat"=>"Jakarta","tanggal_daftar"=>"2024-01-15","status"=>"Aktif","total_pinjaman"=>5],
    ["id"=>"AGT-002","nama"=>"Siti Aminah","email"=>"siti@email.com","telepon"=>"08223","alamat"=>"Bandung","tanggal_daftar"=>"2023-12-10","status"=>"Non-Aktif","total_pinjaman"=>2],
    ["id"=>"AGT-003","nama"=>"Andi Saputra","email"=>"andi@email.com","telepon"=>"08334","alamat"=>"Surabaya","tanggal_daftar"=>"2024-02-01","status"=>"Aktif","total_pinjaman"=>8],
    ["id"=>"AGT-004","nama"=>"Dewi Lestari","email"=>"dewi@email.com","telepon"=>"08445","alamat"=>"Jogja","tanggal_daftar"=>"2023-11-20","status"=>"Aktif","total_pinjaman"=>3],
    ["id"=>"AGT-005","nama"=>"Rudi Hartono","email"=>"rudi@email.com","telepon"=>"08556","alamat"=>"Medan","tanggal_daftar"=>"2024-01-05","status"=>"Non-Aktif","total_pinjaman"=>1],
];

// SEARCH
$keyword = $_GET['search'] ?? '';
if ($keyword != '') {
    $anggota_list = search_nama($anggota_list, $keyword);
}

// SORT
$anggota_list = sort_nama($anggota_list);

// Statistik
$total = hitung_total_anggota($anggota_list);
$aktif = hitung_anggota_aktif($anggota_list);
$nonaktif = $total - $aktif;
$rata = hitung_rata_rata_pinjaman($anggota_list);
$teraktif = cari_anggota_teraktif($anggota_list);

$anggota_aktif = filter_by_status($anggota_list, "Aktif");
$anggota_nonaktif = filter_by_status($anggota_list, "Non-Aktif");
?>

<div class="container mt-5">
    <h1 class="mb-4"><i class="bi bi-people"></i> Sistem Anggota Perpustakaan</h1>

    <!-- SEARCH -->
    <form method="GET" class="mb-3">
        <input type="text" name="search" class="form-control" placeholder="Cari nama anggota..." value="<?= $keyword ?>">
    </form>

    <!-- Dashboard Statistik -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>Total Anggota</h5>
                <h3><?= $total ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>Aktif</h5>
                <h3><?= $aktif ?> (<?= round(($aktif/$total)*100) ?>%)</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>Non-Aktif</h5>
                <h3><?= $nonaktif ?> (<?= round(($nonaktif/$total)*100) ?>%)</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>Rata-rata Pinjaman</h5>
                <h3><?= round($rata,2) ?></h3>
            </div>
        </div>
    </div>

    <!-- Tabel Anggota -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Daftar Anggota</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Pinjaman</th>
                </tr>

                <?php foreach ($anggota_list as $a): ?>
                <tr>
                    <td><?= $a['id'] ?></td>
                    <td><?= $a['nama'] ?></td>
                    <td><?= $a['email'] ?></td>
                    <td>
                        <span class="badge bg-<?= $a['status']=="Aktif" ? "success" : "danger" ?>">
                            <?= $a['status'] ?>
                        </span>
                    </td>
                    <td><?= $a['total_pinjaman'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <!-- Anggota Teraktif -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Anggota Teraktif</h5>
        </div>
        <div class="card-body">
            <h4><?= $teraktif['nama'] ?></h4>
            <p>Total Pinjaman: <?= $teraktif['total_pinjaman'] ?></p>
        </div>
    </div>

    <!-- Aktif vs Non Aktif -->
<div class="row">
    <!-- Anggota Aktif -->
    <div class="col-md-6">
        <div class="card border-success mb-3">
            <div class="card-header bg-success text-white">
                Anggota Aktif
            </div>
            <div class="card-body">
                <ol class="list-group list-group-numbered">
                    <?php foreach ($anggota_aktif as $a): ?>
                    <li class="list-group-item">
                        <?= $a['nama'] ?>
                    </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </div>

    <!-- Anggota Non-Aktif -->
    <div class="col-md-6">
        <div class="card border-danger mb-3">
            <div class="card-header bg-danger text-white">
                Anggota Non-Aktif
            </div>
            <div class="card-body">
                <ol class="list-group list-group-numbered">
                    <?php foreach ($anggota_nonaktif as $a): ?>
                    <li class="list-group-item">
                        <?= $a['nama'] ?>
                    </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>