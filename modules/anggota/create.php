<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/header.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Ambil data
    $kode = trim($_POST['kode_anggota']);
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $tgl_lahir = $_POST['tanggal_lahir'];
    $jk = $_POST['jenis_kelamin'];
    $pekerjaan = trim($_POST['pekerjaan']);

    // VALIDASI REQUIRED
    if (!$kode || !$nama || !$email || !$telepon || !$alamat || !$tgl_lahir || !$jk) {
        $errors[] = "Semua field wajib diisi";
    }

    // VALIDASI EMAIL
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    }

    // VALIDASI TELEPON (08xxxx)
    if (!preg_match('/^08[0-9]{8,11}$/', $telepon)) {
        $errors[] = "Format telepon harus 08xxxxxxxxxx";
    }

    // VALIDASI UMUR
    $umur = date_diff(date_create($tgl_lahir), date_create())->y;
    if ($umur < 10) {
        $errors[] = "Umur minimal 10 tahun";
    }

    // CEK UNIK
    $cek = $conn->prepare("SELECT id_anggota FROM anggota WHERE email=? OR kode_anggota=?");
    $cek->bind_param("ss", $email, $kode);
    $cek->execute();
    if ($cek->get_result()->num_rows > 0) {
        $errors[] = "Email atau kode anggota sudah digunakan";
    }

    // UPLOAD FOTO
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {

        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];

        if (!in_array($ext, $allowed)) {
            $errors[] = "Format foto harus jpg/jpeg/png";
        }

        if ($_FILES['foto']['size'] > 2*1024*1024) {
            $errors[] = "Ukuran foto maksimal 2MB";
        }

        if (empty($errors)) {
            $foto = time() . '.' . $ext;
            move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $foto);
        }
    }

    // SIMPAN DATA
    if (empty($errors)) {

        $tanggal = date('Y-m-d');
        $status = 'Aktif';

        $stmt = $conn->prepare("
            INSERT INTO anggota 
            (kode_anggota,nama,email,telepon,alamat,tanggal_lahir,jenis_kelamin,pekerjaan,tanggal_daftar,status,foto)
            VALUES (?,?,?,?,?,?,?,?,?,?,?)
        ");

        $stmt->bind_param(
            "sssssssssss",
            $kode,$nama,$email,$telepon,$alamat,$tgl_lahir,$jk,$pekerjaan,$tanggal,$status,$foto
        );

        if ($stmt->execute()) {
            header("Location: index.php?success=Data berhasil ditambahkan");
            exit;
        } else {
            $errors[] = "Gagal menyimpan data";
        }
    }
}
?>

<h3>➕ Tambah Anggota</h3>

<?php if($errors): ?>
<div class="alert alert-danger">
    <ul>
        <?php foreach($errors as $e): ?>
        <li><?= $e ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="card p-3">

<div class="row">
    <div class="col-md-6 mb-2">
        <label>Kode Anggota</label>
        <input type="text" name="kode_anggota" class="form-control" required>
    </div>

    <div class="col-md-6 mb-2">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-2">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="col-md-6 mb-2">
        <label>Telepon</label>
        <input type="text" name="telepon" class="form-control" required>
    </div>
</div>

<div class="mb-2">
    <label>Alamat</label>
    <textarea name="alamat" class="form-control" required></textarea>
</div>

<div class="row">
    <div class="col-md-4 mb-2">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control" required>
    </div>

    <div class="col-md-4 mb-2">
        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-control" required>
            <option value="">Pilih</option>
            <option>Laki-laki</option>
            <option>Perempuan</option>
        </select>
    </div>

    <div class="col-md-4 mb-2">
        <label>Pekerjaan</label>
        <input type="text" name="pekerjaan" class="form-control">
    </div>
</div>

<div class="mb-2">
    <label>Foto</label>
    <input type="file" name="foto" class="form-control">
</div>

<button class="btn btn-primary mt-2">Simpan</button>
<a href="index.php" class="btn btn-secondary mt-2">Kembali</a>

</form>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>