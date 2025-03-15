<?php
session_start();

// Cek apakah user sudah login atau belum
if (!isset($_SESSION['nomor_induk'])) {
    header("Location: index.php");
    exit();
}

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "crud_app");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
// Proses tambah data saat form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomor_induk = $_POST['nomor_induk'];
    $nama_karyawan = $_POST['nama_karyawan'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telepon'];
    $jabatan = $_POST['jabatan'];

    // Query insert ke database
    $query = "INSERT INTO karyawan (nomor_induk, nama_karyawan, jenis_kelamin, email, no_telepon, jabatan) 
              VALUES ('$nomor_induk', '$nama_karyawan', '$jenis_kelamin', '$email', '$no_telepon', '$jabatan')";

    if (mysqli_query($conn, $query)) {
        echo "Data berhasil ditambahkan!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Karyawan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2 class="text-center">Tambah Data Karyawan</h2>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nomor Induk:</label>
                        <input type="text" name="nomor_induk" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Karyawan:</label>
                        <input type="text" name="nama_karyawan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin:</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon:</label>
                        <input type="number" name="no_telepon" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan:</label>
                        <input type="text" name="jabatan" class="form-control" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Tambah</button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <a href="lihat.php" class="btn btn-secondary">Kembali ke Halaman Utama</a>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
