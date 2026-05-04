<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/header.php';

$id = $_GET['id'] ?? 0;

// AMBIL DATA LAMA
$stmt = $conn->prepare("SELECT * FROM anggota WHERE id_anggota=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    die("Data tidak ditemukan");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    // AMBIL DATA
    $kode = trim($_POST['kode_anggota']);
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $tgl_lahir = $_POST['tanggal_lahir'];
    $jk = $_POST['jenis_kelamin'];
    $pekerjaan = trim($_POST['pekerjaan']);
    $status = $_POST['status'];

    // VALIDASI
    if (!$kode || !$nama || !$email || !$telepon || !$alamat || !$tgl_lahir || !$jk) {
        $errors[] = "Semua field wajib diisi";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid";
    }

    if (!preg_match('/^08[0-9]{8,11}$/', $telepon)) {
        $errors[] = "Format telepon salah";
    }

    $umur = date_diff(date_create($tgl_lahir), date_create())->y;
    if ($umur < 10) {
        $errors[] = "Minimal umur 10 tahun";
    }

    $cek = $conn->prepare("SELECT id_anggota FROM anggota WHERE (email=? OR kode_anggota=?) AND id_anggota!=?");
    $cek->bind_param("ssi", $email, $kode, $id);
    $cek->execute();
    if ($cek->get_result()->num_rows > 0) {
        $errors[] = "Email atau kode sudah digunakan";
    }

    // HANDLE FOTO 
    $upload_dir = __DIR__ . "/uploads/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // pakai foto lama jika tidak upload baru
    $foto = $data['foto'] ?? null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {

        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];

        if (!in_array($ext, $allowed)) {
            $errors[] = "Format foto harus jpg/jpeg/png";
        }

        if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            $errors[] = "Ukuran maksimal 2MB";
        }

        if (empty($errors)) {

            // hapus foto lama
            if (!empty($data['foto']) && file_exists($upload_dir . $data['foto'])) {
                unlink($upload_dir . $data['foto']);
            }

            // upload baru
            $foto = time() . "." . $ext;
            $target = $upload_dir . $foto;

            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
                $errors[] = "Gagal upload foto";
            }
        }
    }

    // UPDATE DATABASE 
    if (empty($errors)) {

        $stmt = $conn->prepare("
            UPDATE anggota SET
            kode_anggota=?,
            nama=?,
            email=?,
            telepon=?,
            alamat=?,
            tanggal_lahir=?,
            jenis_kelamin=?,
            pekerjaan=?,
            status=?,
            foto=?
            WHERE id_anggota=?
        ");

        if (!$stmt) {
            die("Prepare error: " . $conn->error);
        }

        $stmt->bind_param(
            "ssssssssssi",
            $kode,
            $nama,
            $email,
            $telepon,
            $alamat,
            $tgl_lahir,
            $jk,
            $pekerjaan,
            $status,
            $foto,
            $id
        );

        if (!$stmt->execute()) {
            die("Execute error: " . $stmt->error);
        }

        header("Location: index.php?success=Data berhasil diupdate");
        exit;
    }
}
?>

<h3>Edit Anggota</h3>

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
        <input type="text" name="kode_anggota" class="form-control" value="<?= $data['kode_anggota'] ?>" required>
    </div>

    <div class="col-md-6 mb-2">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="<?= $data['nama'] ?>" required>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-2">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= $data['email'] ?>" required>
    </div>

    <div class="col-md-6 mb-2">
        <label>Telepon</label>
        <input type="text" name="telepon" class="form-control" value="<?= $data['telepon'] ?>" required>
    </div>
</div>

<div class="mb-2">
    <label>Alamat</label>
    <textarea name="alamat" class="form-control" required><?= $data['alamat'] ?></textarea>
</div>

<div class="row">
    <div class="col-md-4 mb-2">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control" value="<?= $data['tanggal_lahir'] ?>" required>
    </div>

    <div class="col-md-4 mb-2">
        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-control" required>
            <option <?= $data['jenis_kelamin']=='Laki-laki'?'selected':'' ?>>Laki-laki</option>
            <option <?= $data['jenis_kelamin']=='Perempuan'?'selected':'' ?>>Perempuan</option>
        </select>
    </div>

    <div class="col-md-4 mb-2">
        <label>Pekerjaan</label>
        <input type="text" name="pekerjaan" class="form-control" value="<?= $data['pekerjaan'] ?>">
    </div>
</div>

<div class="mb-2">
    <label>Status</label>
    <select name="status" class="form-control">
        <option <?= $data['status']=='Aktif'?'selected':'' ?>>Aktif</option>
        <option <?= $data['status']=='Nonaktif'?'selected':'' ?>>Nonaktif</option>
    </select>
</div>

<div class="mb-2">
    <label>Foto</label><br>
    <?php if(!empty($data['foto'])): ?>
        <img src="/perpustakaan/modules/anggota/uploads/<?= htmlspecialchars($data['foto']) ?>" width="80">
    <?php endif; ?>
    <input type="file" name="foto" class="form-control mt-2">
</div>

<button class="btn btn-primary mt-2">Update</button>
<a href="index.php" class="btn btn-secondary mt-2">Kembali</a>

</form>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>