<?php
$page_title = "Data Anggota";
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search
$search = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($search)) {
    $query = "SELECT * FROM anggota 
              WHERE nama LIKE ? OR email LIKE ? OR telepon LIKE ?
              ORDER BY created_at DESC 
              LIMIT ? OFFSET ?";
    
    $search_param = "%$search%";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssii", $search_param, $search_param, $search_param, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    $count = $conn->prepare("SELECT COUNT(*) as total FROM anggota 
                             WHERE nama LIKE ? OR email LIKE ? OR telepon LIKE ?");
    $count->bind_param("sss", $search_param, $search_param, $search_param);
    $count->execute();
    $total_rows = $count->get_result()->fetch_assoc()['total'];

} else {
    $stmt = $conn->prepare("SELECT * FROM anggota ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_rows = $conn->query("SELECT COUNT(*) as total FROM anggota")->fetch_assoc()['total'];
}

$total_pages = ceil($total_rows / $limit);
?>

<div class="container">

    <!-- HEADER -->
    <div class="row mb-3">
        <div class="col-md-6">
            <h2><i class="bi bi-people"></i> Data Anggota Perpustakaan</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="create.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Anggota
            </a>
        </div>
    </div>

    <!-- ALERT -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?= htmlspecialchars($_GET['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-x-circle"></i> <?= htmlspecialchars($_GET['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- SEARCH -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" name="search"
                        value="<?= htmlspecialchars($search) ?>"
                        placeholder="Cari nama, email, telepon...">
                    <button class="btn btn-primary">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <?php if ($search): ?>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                Daftar Anggota
                <?php if ($search): ?>
                    <span class="badge bg-light text-dark">
                        Hasil: "<?= htmlspecialchars($search) ?>"
                    </span>
                <?php endif; ?>
            </h5>
        </div>

        <div class="card-body">
            <?php if ($result->num_rows > 0): ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
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
                                <?php if(!empty($row['foto'])): ?>
                                    <img src="uploads/<?= $row['foto'] ?>" 
                                         class="rounded-circle" 
                                         width="50" height="50">
                                <?php else: ?>
                                    <span class="text-muted">-</span>
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
                                <span class="badge <?= $row['jenis_kelamin']=='Laki-laki'?'bg-primary':'bg-danger' ?>">
                                    <?= $row['jenis_kelamin'] ?>
                                </span>
                            </td>

                            <td>
                                <a href="edit.php?id=<?= $row['id_anggota'] ?>" 
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete.php?id=<?= $row['id_anggota'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Yakin hapus?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <?php if ($total_pages > 1): ?>
            <nav class="mt-3">
                <ul class="pagination justify-content-center">

                    <li class="page-item <?= ($page<=1)?'disabled':'' ?>">
                        <a class="page-link" href="?page=<?= $page-1 ?>&search=<?= $search ?>">Previous</a>
                    </li>

                    <?php for($i=1;$i<=$total_pages;$i++): ?>
                    <li class="page-item <?= ($page==$i)?'active':'' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= $search ?>">
                            <?= $i ?>
                        </a>
                    </li>
                    <?php endfor; ?>

                    <li class="page-item <?= ($page>=$total_pages)?'disabled':'' ?>">
                        <a class="page-link" href="?page=<?= $page+1 ?>&search=<?= $search ?>">Next</a>
                    </li>

                </ul>
            </nav>
            <?php endif; ?>

            <!-- INFO -->
            <div class="alert alert-info mt-3 mb-0">
                <i class="bi bi-info-circle"></i>
                <strong>Total:</strong> <?= $total_rows ?> anggota
                <?php if ($search): ?>
                    | <strong>Ditemukan:</strong> <?= $result->num_rows ?>
                <?php endif; ?>
                | <strong>Halaman:</strong> <?= $page ?> dari <?= $total_pages ?>
            </div>

            <?php else: ?>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-exclamation-triangle"></i>
                    <?php if ($search): ?>
                        Tidak ada data ditemukan
                    <?php else: ?>
                        Belum ada data anggota
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
if (isset($stmt)) $stmt->close();
if (isset($count)) $count->close();
?>