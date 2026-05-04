<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/header.php';

// PAGINATION
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// SEARCH + FILTER
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$jk = $_GET['jk'] ?? '';

$where = [];
$params = [];
$types = "";

// SEARCH
if ($search) {
    $where[] = "(nama LIKE ? OR email LIKE ? OR telepon LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "sss";
}

// FILTER STATUS
if ($status) {
    $where[] = "status = ?";
    $params[] = $status;
    $types .= "s";
}

// FILTER JK
if ($jk) {
    $where[] = "jenis_kelamin = ?";
    $params[] = $jk;
    $types .= "s";
}

$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

// QUERY DATA
$query = "SELECT * FROM anggota $whereSQL ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);

// bind dinamis
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// COUNT TOTAL
$countQuery = "SELECT COUNT(*) as total FROM anggota $whereSQL";
$stmtCount = $conn->prepare($countQuery);

if ($types != "ii") {
    $stmtCount->bind_param(substr($types, 0, -2), ...array_slice($params, 0, -2));
}
$stmtCount->execute();
$total_rows = $stmtCount->get_result()->fetch_assoc()['total'];

$total_pages = ceil($total_rows / $limit);

// DASHBOARD
$total = $conn->query("SELECT COUNT(*) as t FROM anggota")->fetch_assoc()['t'];
$aktif = $conn->query("SELECT COUNT(*) as t FROM anggota WHERE status='Aktif'")->fetch_assoc()['t'];
$nonaktif = $conn->query("SELECT COUNT(*) as t FROM anggota WHERE status='Nonaktif'")->fetch_assoc()['t'];
?>

<h3 class="mb-3">Data Anggota</h3>

<?php require_once __DIR__ . '/../../includes/alerts.php'; ?>

<!-- DASHBOARD -->
<div class="row mb-3">
    <div class="col-md-4"><div class="alert alert-primary">Total: <?= $total ?></div></div>
    <div class="col-md-4"><div class="alert alert-success">Aktif: <?= $aktif ?></div></div>
    <div class="col-md-4"><div class="alert alert-danger">Nonaktif: <?= $nonaktif ?></div></div>
</div>

<!-- FILTER -->
<form method="GET" class="row mb-3">
    <div class="col-md-3">
        <input type="text" name="search" value="<?= $search ?>" class="form-control" placeholder="Cari nama/email/telepon">
    </div>

    <div class="col-md-3">
        <select name="status" class="form-control">
            <option value="">Semua Status</option>
            <option value="Aktif" <?= $status=='Aktif'?'selected':'' ?>>Aktif</option>
            <option value="Nonaktif" <?= $status=='Nonaktif'?'selected':'' ?>>Nonaktif</option>
        </select>
    </div>

    <div class="col-md-3">
        <select name="jk" class="form-control">
            <option value="">Semua JK</option>
            <option value="Laki-laki" <?= $jk=='Laki-laki'?'selected':'' ?>>Laki-laki</option>
            <option value="Perempuan" <?= $jk=='Perempuan'?'selected':'' ?>>Perempuan</option>
        </select>
    </div>

    <div class="col-md-3">
        <button class="btn btn-primary">Filter</button>
        <a href="index.php" class="btn btn-secondary">Reset</a>
        <a href="export.php" class="btn btn-success">Export</a>
    </div>
</form>

<a href="create.php" class="btn btn-success mb-3">+ Tambah Anggota</a>

<!-- TABLE -->
<div class="table-responsive">
<table class="table table-hover">
<thead class="table-dark">
<tr>
    <th>No</th>
    <th>Foto</th>
    <th>Nama</th>
    <th>Email</th>
    <th>Telepon</th>
    <th>Status</th>
    <th>JK</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
<?php $no = $offset + 1; ?>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $no++ ?></td>

    <td>
    <?php if (!empty($row['foto'])): ?>
        <img src="/perpustakaan/modules/anggota/uploads/<?= htmlspecialchars($row['foto']) ?>" width="50">
    <?php endif; ?>
    </td>

    <td><?= htmlspecialchars($row['nama']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= htmlspecialchars($row['telepon']) ?></td>

    <td>
        <span class="badge <?= $row['status']=='Aktif'?'bg-success':'bg-danger' ?>">
            <?= $row['status'] ?>
        </span>
    </td>

    <td>
        <span class="badge <?= $row['jenis_kelamin']=='Laki-laki'?'bg-primary':'bg-warning' ?>">
            <?= $row['jenis_kelamin'] ?>
        </span>
    </td>

    <td>
        <a href="edit.php?id=<?= $row['id_anggota'] ?>" class="btn btn-sm btn-warning">Edit</a>
        <a href="delete.php?id=<?= $row['id_anggota'] ?>"
           onclick="return confirm('Yakin hapus?')"
           class="btn btn-sm btn-danger">Hapus</a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

<!-- PAGINATION -->
<nav>
<ul class="pagination">
<?php for($i=1;$i<=$total_pages;$i++): ?>
<li class="page-item <?= $page==$i?'active':'' ?>">
    <a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>&status=<?= $status ?>&jk=<?= $jk ?>">
        <?= $i ?>
    </a>
</li>
<?php endfor; ?>
</ul>
</nav>

<div class="alert alert-info mt-3">
    Total: <?= $total_rows ?> anggota
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>