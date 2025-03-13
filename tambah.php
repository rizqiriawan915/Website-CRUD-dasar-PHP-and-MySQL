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
    <link rel="stylesheet" href="./styles/tambah.css">
    <title>Tambah Karyawan</title>
</head>
<body>

<h2>Tambah Data Karyawan</h2>
<form method="POST">
    <label>Nomor Induk:</label>
    <input type="text" name="nomor_induk" required><br>

    <label>Nama Karyawan:</label>
    <input type="text" name="nama_karyawan" required><br>

    <label>Jenis Kelamin:</label>
    <select name="jenis_kelamin" required>
        <option value="Laki-laki">Laki-laki</option>
        <option value="Perempuan">Perempuan</option>
    </select><br>

    <label>Email:</label>
    <input type="email" name="email" required><br>

    <label>No. Telepon:</label>
    <input type="number" name="no_telepon" required><br>

    <label>Jabatan:</label>
    <input type="text" name="jabatan" required><br>

    <button type="submit">Tambah</button>
</form>

<a href="lihat.php">Kembali ke Halaman Utama</a>

</body>
</html>