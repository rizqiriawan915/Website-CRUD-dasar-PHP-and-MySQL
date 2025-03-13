<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['nomor_induk'])) {
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

// Fetch all data
$query = "SELECT * FROM karyawan";
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
        <a href="logout.php" class="btn btn-logout">Logout</a>
    </div>
    
    <div class="welcome">
        <p>Selamat datang, <strong><?php echo $username; ?>
    </div>
    
    <?php if (isset($success_message)) { ?>
        <div class="message success"><?php echo $success_message; ?></div>
    <?php } ?>
    
    <?php if (isset($error_message)) { ?>
        <div class="message error"><?php echo $error_message; ?></div>
    <?php } ?>
    
    <a href="tambah.php" class="btn btn-add">Tambah Data</a>
    
    <table>
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
        
        <?php
        if (mysqli_num_rows($result) > 0) {
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . $row['nomor_induk'] . "</td>";
                echo "<td>" . $row['nama_karyawan'] . "</td>";
                echo "<td>" . $row['jenis_kelamin'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['no_telepon'] . "</td>";
                echo "<td>" . $row['jabatan'] . "</td>";
                echo "<td>
                        <a href='ubah.php?id=" . $row['nomor_induk'] . "' class='btn btn-edit'>Edit</a>
                        <a href='lihat.php?delete=" . $row['nomor_induk'] . "' class='btn btn-delete' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\");'>Hapus</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8' style='text-align: center;'>Tidak ada data karyawan</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php mysqli_close($conn); ?>