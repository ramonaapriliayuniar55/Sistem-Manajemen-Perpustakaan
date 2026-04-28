<?php
$errors = [];
$data = [
    "nama" => "",
    "email" => "",
    "telepon" => "",
    "alamat" => "",
    "jk" => "",
    "tgl_lahir" => "",
    "pekerjaan" => ""
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ambil data
    foreach ($data as $key => $val) {
        $data[$key] = trim($_POST[$key] ?? '');
    }

    // VALIDASI

    // Nama
    if (!$data['nama']) {
        $errors['nama'] = "Nama wajib diisi";
    } elseif (strlen($data['nama']) < 3) {
        $errors['nama'] = "Minimal 3 karakter";
    }

    // Email
    if (!$data['email']) {
        $errors['email'] = "Email wajib diisi";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Format email tidak valid";
    }

    // Telepon
    if (!$data['telepon']) {
        $errors['telepon'] = "Telepon wajib diisi";
    } elseif (!preg_match('/^08[0-9]{8,11}$/', $data['telepon'])) {
        $errors['telepon'] = "Format harus 08xxxxxxxxxx (10-13 digit)";
    }

    // Alamat
    if (!$data['alamat']) {
        $errors['alamat'] = "Alamat wajib diisi";
    } elseif (strlen($data['alamat']) < 10) {
        $errors['alamat'] = "Minimal 10 karakter";
    }

    // Jenis kelamin
    if (!$data['jk']) {
        $errors['jk'] = "Pilih jenis kelamin";
    }

    // Tanggal lahir
    if (!$data['tgl_lahir']) {
        $errors['tgl_lahir'] = "Tanggal lahir wajib diisi";
    } else {
        $umur = date_diff(date_create($data['tgl_lahir']), date_create('today'))->y;
        if ($umur < 10) {
            $errors['tgl_lahir'] = "Umur minimal 10 tahun";
        }
    }

    // Pekerjaan
    if (!$data['pekerjaan']) {
        $errors['pekerjaan'] = "Pilih pekerjaan";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>📋 Form Registrasi Anggota</h2>

<form method="POST" class="row g-3">

<!-- Nama -->
<div class="col-md-6">
    <label>Nama Lengkap</label>
    <input type="text" name="nama" class="form-control <?= isset($errors['nama'])?'is-invalid':'' ?>" value="<?= $data['nama'] ?>">
    <div class="invalid-feedback"><?= $errors['nama'] ?? '' ?></div>
</div>

<!-- Email -->
<div class="col-md-6">
    <label>Email</label>
    <input type="email" name="email" class="form-control <?= isset($errors['email'])?'is-invalid':'' ?>" value="<?= $data['email'] ?>">
    <div class="invalid-feedback"><?= $errors['email'] ?? '' ?></div>
</div>

<!-- Telepon -->
<div class="col-md-6">
    <label>Telepon</label>
    <input type="text" name="telepon" class="form-control <?= isset($errors['telepon'])?'is-invalid':'' ?>" value="<?= $data['telepon'] ?>">
    <div class="invalid-feedback"><?= $errors['telepon'] ?? '' ?></div>
</div>

<!-- Alamat -->
<div class="col-md-12">
    <label>Alamat</label>
    <textarea name="alamat" class="form-control <?= isset($errors['alamat'])?'is-invalid':'' ?>"><?= $data['alamat'] ?></textarea>
    <div class="invalid-feedback"><?= $errors['alamat'] ?? '' ?></div>
</div>

<!-- Jenis Kelamin -->
<div class="col-md-6">
    <label>Jenis Kelamin</label><br>
    <input type="radio" name="jk" value="Laki-laki" <?= $data['jk']=="Laki-laki"?'checked':'' ?>> Laki-laki
    <input type="radio" name="jk" value="Perempuan" <?= $data['jk']=="Perempuan"?'checked':'' ?>> Perempuan
    <div class="text-danger"><?= $errors['jk'] ?? '' ?></div>
</div>

<!-- Tanggal Lahir -->
<div class="col-md-6">
    <label>Tanggal Lahir</label>
    <input type="date" name="tgl_lahir" class="form-control <?= isset($errors['tgl_lahir'])?'is-invalid':'' ?>" value="<?= $data['tgl_lahir'] ?>">
    <div class="invalid-feedback"><?= $errors['tgl_lahir'] ?? '' ?></div>
</div>

<!-- Pekerjaan -->
<div class="col-md-6">
    <label>Pekerjaan</label>
    <select name="pekerjaan" class="form-control <?= isset($errors['pekerjaan'])?'is-invalid':'' ?>">
        <option value="">Pilih</option>
        <option <?= $data['pekerjaan']=="Pelajar"?'selected':'' ?>>Pelajar</option>
        <option <?= $data['pekerjaan']=="Mahasiswa"?'selected':'' ?>>Mahasiswa</option>
        <option <?= $data['pekerjaan']=="Pegawai"?'selected':'' ?>>Pegawai</option>
        <option <?= $data['pekerjaan']=="Lainnya"?'selected':'' ?>>Lainnya</option>
    </select>
    <div class="invalid-feedback"><?= $errors['pekerjaan'] ?? '' ?></div>
</div>

<div class="col-12">
    <button class="btn btn-primary">Daftar</button>
</div>

</form>

<hr>

<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($errors)): ?>
<div class="card mt-4">
    <div class="card-header bg-success text-white">Registrasi Berhasil</div>
    <div class="card-body">
        <p><b>Nama:</b> <?= $data['nama'] ?></p>
        <p><b>Email:</b> <?= $data['email'] ?></p>
        <p><b>Telepon:</b> <?= $data['telepon'] ?></p>
        <p><b>Alamat:</b> <?= $data['alamat'] ?></p>
        <p><b>Jenis Kelamin:</b> <?= $data['jk'] ?></p>
        <p><b>Tanggal Lahir:</b> <?= $data['tgl_lahir'] ?></p>
        <p><b>Pekerjaan:</b> <?= $data['pekerjaan'] ?></p>
    </div>
</div>
<?php endif; ?>

</body>
</html>