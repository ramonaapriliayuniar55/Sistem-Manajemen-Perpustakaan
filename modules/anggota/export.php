<?php
require_once __DIR__ . '/../../config/database.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=data_anggota.xls");

echo "No\tNama\tEmail\tTelepon\tStatus\n";

$no = 1;
$data = $conn->query("SELECT * FROM anggota");

while ($row = $data->fetch_assoc()) {
    echo $no++ . "\t";
    echo $row['nama'] . "\t";
    echo $row['email'] . "\t";
    echo $row['telepon'] . "\t";
    echo $row['status'] . "\n";
}