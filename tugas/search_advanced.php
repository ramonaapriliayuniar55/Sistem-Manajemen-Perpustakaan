<?php
session_start();

// Data buku 
$buku_list = [
    ["kode"=>"IF001","judul"=>"Dasar Pemrograman C++","kategori"=>"Pemrograman","pengarang"=>"Budi Raharjo","penerbit"=>"Informatika","tahun"=>2021,"harga"=>85000,"stok"=>5],
    ["kode"=>"IF002","judul"=>"Algoritma dan Struktur Data","kategori"=>"Algoritma","pengarang"=>"Rinaldi Munir","penerbit"=>"Informatika","tahun"=>2020,"harga"=>95000,"stok"=>2],
    ["kode"=>"IF003","judul"=>"Pemrograman Web PHP","kategori"=>"Web","pengarang"=>"Andi","penerbit"=>"Erlangga","tahun"=>2022,"harga"=>90000,"stok"=>0],
    ["kode"=>"IF004","judul"=>"Basis Data MySQL","kategori"=>"Database","pengarang"=>"Bunafit Nugroho","penerbit"=>"Andi","tahun"=>2019,"harga"=>80000,"stok"=>3],
    ["kode"=>"IF005","judul"=>"Machine Learning Dasar","kategori"=>"AI","pengarang"=>"Eka","penerbit"=>"Gramedia","tahun"=>2023,"harga"=>120000,"stok"=>4],
    ["kode"=>"IF006","judul"=>"Jaringan Komputer","kategori"=>"Networking","pengarang"=>"Tanenbaum","penerbit"=>"Prenhall","tahun"=>2018,"harga"=>110000,"stok"=>1],
    ["kode"=>"IF007","judul"=>"Pemrograman Python","kategori"=>"Pemrograman","pengarang"=>"Guido","penerbit"=>"O'Reilly","tahun"=>2021,"harga"=>100000,"stok"=>6],
    ["kode"=>"IF008","judul"=>"Cyber Security","kategori"=>"Security","pengarang"=>"Kevin Mitnick","penerbit"=>"Gramedia","tahun"=>2020,"harga"=>130000,"stok"=>2],
    ["kode"=>"IF009","judul"=>"Data Science","kategori"=>"AI","pengarang"=>"Andrew Ng","penerbit"=>"Coursera","tahun"=>2022,"harga"=>140000,"stok"=>0],
    ["kode"=>"IF010","judul"=>"Cloud Computing","kategori"=>"Cloud","pengarang"=>"Amazon","penerbit"=>"AWS","tahun"=>2023,"harga"=>150000,"stok"=>5],

    ["kode"=>"IF011","judul"=>"Pemrograman Java","kategori"=>"Pemrograman","pengarang"=>"Herbert Schildt","penerbit"=>"Oracle","tahun"=>2020,"harga"=>115000,"stok"=>3],
    ["kode"=>"IF012","judul"=>"React JS untuk Pemula","kategori"=>"Web","pengarang"=>"Jordan Walke","penerbit"=>"Meta","tahun"=>2022,"harga"=>125000,"stok"=>4],
    ["kode"=>"IF013","judul"=>"Node.js Backend Development","kategori"=>"Web","pengarang"=>"Ryan Dahl","penerbit"=>"OpenJS","tahun"=>2021,"harga"=>135000,"stok"=>2],
    ["kode"=>"IF014","judul"=>"Artificial Intelligence","kategori"=>"AI","pengarang"=>"Stuart Russell","penerbit"=>"Pearson","tahun"=>2019,"harga"=>160000,"stok"=>1],
    ["kode"=>"IF015","judul"=>"Deep Learning","kategori"=>"AI","pengarang"=>"Ian Goodfellow","penerbit"=>"MIT Press","tahun"=>2018,"harga"=>170000,"stok"=>2],
    ["kode"=>"IF016","judul"=>"Linux Administration","kategori"=>"Networking","pengarang"=>"Linus Torvalds","penerbit"=>"Linux Org","tahun"=>2021,"harga"=>105000,"stok"=>3],
    ["kode"=>"IF017","judul"=>"Ethical Hacking","kategori"=>"Security","pengarang"=>"CEH","penerbit"=>"EC-Council","tahun"=>2022,"harga"=>145000,"stok"=>0],
    ["kode"=>"IF018","judul"=>"Big Data Analytics","kategori"=>"Database","pengarang"=>"Tom White","penerbit"=>"O'Reilly","tahun"=>2020,"harga"=>155000,"stok"=>2],
    ["kode"=>"IF019","judul"=>"Docker & Kubernetes","kategori"=>"Cloud","pengarang"=>"Kelsey Hightower","penerbit"=>"Google","tahun"=>2023,"harga"=>165000,"stok"=>4],
    ["kode"=>"IF020","judul"=>"UI/UX Design","kategori"=>"Web","pengarang"=>"Don Norman","penerbit"=>"Nielsen","tahun"=>2019,"harga"=>95000,"stok"=>6],
];

// Ambil GET
$keyword = $_GET['keyword'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$min_harga = $_GET['min_harga'] ?? '';
$max_harga = $_GET['max_harga'] ?? '';
$tahun = $_GET['tahun'] ?? '';
$status = $_GET['status'] ?? 'semua';
$sort = $_GET['sort'] ?? 'judul';

$errors = [];

// Validasi
if ($min_harga && $max_harga && $min_harga > $max_harga) {
    $errors[] = "Harga minimum tidak boleh lebih besar dari maksimum";
}

if ($tahun && ($tahun < 1900 || $tahun > date("Y"))) {
    $errors[] = "Tahun tidak valid";
}

// Filter
$hasil = array_filter($buku_list, function($b) use ($keyword,$kategori,$min_harga,$max_harga,$tahun,$status){

    if ($keyword && (stripos($b['judul'].$b['pengarang'], $keyword)) === false) return false;
    if ($kategori && $b['kategori'] != $kategori) return false;
    if ($min_harga && $b['harga'] < $min_harga) return false;
    if ($max_harga && $b['harga'] > $max_harga) return false;
    if ($tahun && $b['tahun'] != $tahun) return false;
    if ($status=="tersedia" && $b['stok']<=0) return false;
    if ($status=="habis" && $b['stok']>0) return false;

    return true;
});

// Sorting
usort($hasil, function($a,$b) use ($sort){
    return $a[$sort] <=> $b[$sort];
});

// ================= PAGINATION =================
$per_page = 10;
$page = max(1, (int)($_GET['page'] ?? 1));

$total_data = count($hasil);
$total_page = ceil($total_data / $per_page);

if ($page > $total_page) $page = $total_page;

$start = ($page - 1) * $per_page;

// ambil data sesuai halaman
$hasil = array_slice($hasil, $start, $per_page);

// Simpan ke session (recent search)
if ($keyword) {
    $_SESSION['recent'][] = $keyword;
}

// Export CSV
if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="buku.csv"');

    $output = fopen("php://output", "w");
    fputcsv($output, ["Kode","Judul","Kategori","Pengarang","Tahun","Harga","Stok"]);

    foreach ($hasil as $b) {
        fputcsv($output, $b);
    }
    fclose($output);
    exit;
}

// Highlight
function highlight($text, $keyword) {
    if (!$keyword) return $text;
    return str_ireplace($keyword, "<mark>$keyword</mark>", $text);
}

$total = $total_data;
?>

<!DOCTYPE html>
<html>
<head>
<title>Search Buku Informatika</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>📚 Pencarian Buku </h2>

<?php if ($errors): ?>
<div class="alert alert-danger">
<?php foreach ($errors as $e) echo "<div>$e</div>"; ?>
</div>
<?php endif; ?>

<form method="GET" class="row g-2">

<input type="text" name="keyword" class="form-control" placeholder="Keyword" value="<?= $keyword ?>">

<select name="kategori" class="form-control">
<option value="">Semua</option>
<option <?= $kategori=="Pemrograman"?"selected":"" ?>>Pemrograman</option>
<option <?= $kategori=="AI"?"selected":"" ?>>AI</option>
<option <?= $kategori=="Web"?"selected":"" ?>>Web</option>
<option <?= $kategori=="Database"?"selected":"" ?>>Database</option>
<option <?= $kategori=="Security"?"selected":"" ?>>Security</option>
<option <?= $kategori=="Algoritma"?"selected":"" ?>>Algoritma</option>
<option <?= $kategori=="Networking"?"selected":"" ?>>Networking</option>
<option <?= $kategori=="Cloud"?"selected":"" ?>>Cloud</option>
</select>

<input type="number" name="min_harga" placeholder="Min Harga" class="form-control" value="<?= $min_harga ?>">
<input type="number" name="max_harga" placeholder="Max Harga" class="form-control" value="<?= $max_harga ?>">
<input type="number" name="tahun" placeholder="Tahun" class="form-control" value="<?= $tahun ?>">

<div>
<label><input type="radio" name="status" value="semua" <?= $status=="semua"?"checked":"" ?>> Semua</label>
<label><input type="radio" name="status" value="tersedia" <?= $status=="tersedia"?"checked":"" ?>> Tersedia</label>
<label><input type="radio" name="status" value="habis" <?= $status=="habis"?"checked":"" ?>> Habis</label>
</div>

<select name="sort" class="form-control">
<option value="judul">Judul</option>
<option value="harga">Harga</option>
<option value="tahun">Tahun</option>
</select>

<button class="btn btn-primary">Cari</button>
<button name="export" class="btn btn-success">Export CSV</button>

</form>

<hr>

<h5>Total ditemukan: <?= $total ?></h5>

<table class="table table-bordered">
<tr>
<th>Kode</th><th>Judul</th><th>Kategori</th><th>Pengarang</th><th>Tahun</th><th>Harga</th><th>Stok</th>
</tr>

<?php foreach ($hasil as $b): ?>
<tr>
<td><?= $b['kode'] ?></td>
<td><?= highlight($b['judul'], $keyword) ?></td>
<td><?= $b['kategori'] ?></td>
<td><?= highlight($b['pengarang'], $keyword) ?></td>
<td><?= $b['tahun'] ?></td>
<td><?= $b['harga'] ?></td>
<td><?= $b['stok'] ?></td>
</tr>
<?php endforeach; ?>

</table>

<nav>
<ul class="pagination">

<!-- PREV -->
<?php if ($page > 1): ?>
<li class="page-item">
    <a class="page-link" href="?<?= http_build_query(array_merge($_GET,['page'=>$page-1])) ?>">
        ← Prev
    </a>
</li>
<?php endif; ?>

<!-- NOMOR -->
<?php for ($i=1; $i<=$total_page; $i++): ?>
<li class="page-item <?= ($i==$page)?'active':'' ?>">
    <a class="page-link" href="?<?= http_build_query(array_merge($_GET,['page'=>$i])) ?>">
        <?= $i ?>
    </a>
</li>
<?php endfor; ?>

<!-- NEXT -->
<?php if ($page < $total_page): ?>
<li class="page-item">
    <a class="page-link" href="?<?= http_build_query(array_merge($_GET,['page'=>$page+1])) ?>">
        Next →
    </a>
</li>
<?php endif; ?>

</ul>
</nav>

<?php if (!empty($_SESSION['recent'])): ?>
<h5>Recent Search:</h5>
<ul>
<?php foreach (array_unique($_SESSION['recent']) as $r): ?>
<li><?= $r ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

</body>
</html>