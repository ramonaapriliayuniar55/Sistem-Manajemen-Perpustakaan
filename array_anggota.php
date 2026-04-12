<?php
$anggota_list = [
    [
        "id" => "AGT-001",
        "nama" => "Budi Santoso",
        "email" => "budi@email.com",
        "telepon" => "081234567890",
        "alamat" => "Jakarta",
        "tanggal_daftar" => "2024-01-15",
        "status" => "Aktif",
        "total_pinjaman" => 5
    ],
    [
        "id" => "AGT-002",
        "nama" => "Siti Aminah",
        "email" => "siti@email.com",
        "telepon" => "082233445566",
        "alamat" => "Bandung",
        "tanggal_daftar" => "2023-12-10",
        "status" => "Non-Aktif",
        "total_pinjaman" => 2
    ],
    [
        "id" => "AGT-003",
        "nama" => "Andi Saputra",
        "email" => "andi@email.com",
        "telepon" => "083344556677",
        "alamat" => "Surabaya",
        "tanggal_daftar" => "2024-02-01",
        "status" => "Aktif",
        "total_pinjaman" => 8
    ],
    [
        "id" => "AGT-004",
        "nama" => "Dewi Lestari",
        "email" => "dewi@email.com",
        "telepon" => "084455667788",
        "alamat" => "Yogyakarta",
        "tanggal_daftar" => "2023-11-20",
        "status" => "Aktif",
        "total_pinjaman" => 3
    ],
    [
        "id" => "AGT-005",
        "nama" => "Rudi Hartono",
        "email" => "rudi@email.com",
        "telepon" => "085566778899",
        "alamat" => "Medan",
        "tanggal_daftar" => "2024-01-05",
        "status" => "Non-Aktif",
        "total_pinjaman" => 1
    ]
];

// ================== LOGIKA ==================

// Total anggota
$total = count($anggota_list);

// Hitung aktif & non aktif
$aktif = 0;
$nonaktif = 0;
$total_pinjaman = 0;

foreach ($anggota_list as $a) {
    if ($a['status'] == "Aktif") {
        $aktif++;
    } else {
        $nonaktif++;
    }
    $total_pinjaman += $a['total_pinjaman'];
}

// Persentase
$persen_aktif = ($aktif / $total) * 100;
$persen_nonaktif = ($nonaktif / $total) * 100;

// Rata-rata
$rata = $total_pinjaman / $total;

// Anggota teraktif
$teraktif = $anggota_list[0];
foreach ($anggota_list as $a) {
    if ($a['total_pinjaman'] > $teraktif['total_pinjaman']) {
        $teraktif = $a;
    }
}

// Filter
$anggota_aktif = [];
$anggota_nonaktif = [];

foreach ($anggota_list as $a) {
    if ($a['status'] == "Aktif") {
        $anggota_aktif[] = $a;
    } else {
        $anggota_nonaktif[] = $a;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Array Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="mb-4">Data Anggota Perpustakaan</h1>

    <!-- Statistik -->
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
                <h3><?= $aktif ?> (<?= round($persen_aktif) ?>%)</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>Non-Aktif</h5>
                <h3><?= $nonaktif ?> (<?= round($persen_nonaktif) ?>%)</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5>Rata-rata Pinjaman</h5>
                <h3><?= round($rata,2) ?></h3>
            </div>
        </div>
    </div>

    <!-- Anggota Teraktif -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            Anggota Teraktif
        </div>
        <div class="card-body">
            <h4><?= $teraktif['nama'] ?></h4>
            <p>Total Pinjaman: <?= $teraktif['total_pinjaman'] ?></p>
        </div>
    </div>

    <!-- Tabel Anggota -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Daftar Anggota
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