<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['nomor_induk'])) {
    header("Location: index.php");
    exit();
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "crud_app");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Get admin username info
$nomor_induk = $_SESSION['nomor_induk'];
$user_query = "SELECT username FROM users WHERE nomor_induk = '$nomor_induk'";
$user_result = mysqli_query($conn, $user_query);
$user_data = mysqli_fetch_assoc($user_result);
$username = $user_data['username'];

// Delete functionality
if (isset($_GET['delete'])) {
    $nomor_induk_karyawan = $_GET['delete'];
    $delete_query = "DELETE FROM karyawan WHERE nomor_induk = '$nomor_induk_karyawan'";
    
    if (mysqli_query($conn, $delete_query)) {
        $success_message = "Data berhasil dihapus!";
    } else {
        $error_message = "Gagal menghapus data: " . mysqli_error($conn);
    }
}

// **Pagination**
$per_page = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Ambil nomor halaman dari URL
$start = ($page - 1) * $per_page; // Tentukan titik awal data

// Hitung total data
$total_query = "SELECT COUNT(*) as total FROM karyawan";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_data = $total_row['total'];

$total_pages = ceil($total_data / $per_page); // Hitung total halaman

// Ambil data sesuai halaman
$query = "SELECT * FROM karyawan LIMIT $start, $per_page";
$result = mysqli_query($conn, $query);
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
                    <!-- Data dari AJAX akan masuk sini -->
                    <tr>
                        <td>1</td>
                        <td>12345</td>
                        <td>John Doe</td>
                        <td>Laki-laki</td>
                        <td>john.doe@example.com</td>
                        <td>08123456789</td>
                        <td>Manager</td>
                        <td>
                            <a href="detail.php?id=12345" class="btn btn-info btn-sm">Detail</a>
                            <a href="edit.php?id=12345" class="btn btn-warning btn-sm">Edit</a>
                            <a href="hapus.php?id=12345" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tombol Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <button id="prev-btn" class="btn btn-secondary">Sebelumnya</button>
            <span id="page-number" class="fw-bold">Halaman <?php echo $page; ?></span>
            <button id="next-btn" class="btn btn-secondary">Selanjutnya</button>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./javascript/ajax.js"></script>
</body>
</html>
<?php mysqli_close($conn); ?>