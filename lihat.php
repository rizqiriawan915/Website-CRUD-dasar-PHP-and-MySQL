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
    <link rel="stylesheet" href="./styles/lihat.css">
    <title>Lihat Data Karyawan</title>
</head>
<body>
    <div class="header-container">
        <h2>Data Karyawan</h2>
        <a href="?action=logout" class="btn btn-logout">Logout</a>
    </div>
    
    <div class="welcome">
        <p>Selamat datang, <strong><?php echo $username; ?></strong></p>
    </div>
    <div class="action-container">
        <a href="tambah.php" class="btn btn-add">Tambah Data</a>
        
        <div class="search-container">
            <label for="search" class="search-label">Cari:</label>
            <input type="text" id="search" class="search-box" placeholder="Cari data karyawan..." autocomplete="off">
        </div>
    </div>
    
    <div id="karyawan-data">
    <table id="karyawanTable">
        <thead>
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
        </tbody>
    </table>
</div>

<!-- Tombol Pagination -->
<div class="pagination" data-page="<?php echo $page; ?>" data-total="<?php echo $total_pages; ?>">
    <button id="prev-btn" class="btn">Sebelumnya</button>
    <span id="page-number"><?php echo $page; ?></span>
    <button id="next-btn" class="btn">Selanjutnya</button>
</div>


    <script src="./javascript/ajax.js"></script>
</body>
</html>

<?php mysqli_close($conn); ?>
