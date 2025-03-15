<?php
session_start();
// Include file function.php
require_once 'function.php';

// Check if user is logged in
checkUserLogin();

// Handle logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    logout();
    redirect('index.php');
}

// Get admin username info
$nomor_induk = $_SESSION['nomor_induk'];
$user_data = getUserData($nomor_induk);
$username = $user_data['username'];

$success_message = '';
$error_message = '';

// Delete functionality
if (isset($_GET['delete'])) {
    $nomor_induk_karyawan = $_GET['delete'];
    $delete_result = deleteKaryawan($nomor_induk_karyawan);
    
    if ($delete_result === true) {
        $success_message = "Data berhasil dihapus!";
    } else {
        $error_message = "Gagal menghapus data: " . $delete_result;
    }
}

// Pagination
$per_page = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Ambil nomor halaman dari URL
$start = ($page - 1) * $per_page; // Tentukan titik awal data

// Hitung total data
$total_data = countKaryawan();
$total_pages = ceil($total_data / $per_page); // Hitung total halaman

// Ambil data sesuai halaman
$karyawan_data = getKaryawanWithPagination($start, $per_page);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Data Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-primary">Data Karyawan</h2>
            <a href="?action=logout" class="btn btn-danger">Logout</a>
        </div>
        
        <div class="alert alert-info">Selamat datang, <strong><?php echo $username; ?></strong></div>
        
        <?php if (!empty($success_message)): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $success_message; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
        <?php endif; ?>
        
        <div class="d-flex justify-content-between mb-3">
            <a href="tambah.php" class="btn btn-success">Tambah Data</a>
            <div class="input-group w-50">
                <span class="input-group-text">🔍</span>
                <input type="text" id="search" class="form-control" placeholder="Cari data karyawan..." autocomplete="off">
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nomor Induk</th>
                        <th>Nama Karyawan</th>
                        <th>Jenis Kelamin</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Jabatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="karyawan-body">
                    <?php
                    $no = $start + 1;
                    foreach ($karyawan_data as $row) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['nomor_induk']; ?></td>
                        <td><?php echo $row['nama_karyawan']; ?></td>
                        <td><?php echo $row['jenis_kelamin']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['no_telepon']; ?></td>
                        <td><?php echo $row['jabatan']; ?></td>
                        <td>
                            <a href="detail.php?id=<?php echo $row['nomor_induk']; ?>" class="btn btn-info btn-sm">Detail</a>
                            <a href="ubah.php?id=<?php echo $row['nomor_induk']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="?delete=<?php echo $row['nomor_induk']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                    
                    <?php if (empty($karyawan_data)): ?>
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data karyawan</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Tombol Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <a href="?page=<?php echo max(1, $page - 1); ?>" class="btn btn-secondary <?php echo ($page == 1) ? 'disabled' : ''; ?>">Sebelumnya</a>
            <span id="page-number" class="fw-bold">Halaman <?php echo $page; ?> dari <?php echo $total_pages; ?></span>
            <a href="?page=<?php echo min($total_pages, $page + 1); ?>" class="btn btn-secondary <?php echo ($page == $total_pages) ? 'disabled' : ''; ?>">Selanjutnya</a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="./javascript/ajax.js"></script> -->
</body>
</html>
