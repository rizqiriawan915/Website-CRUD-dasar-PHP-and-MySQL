<?php
// Koneksi database
$conn = mysqli_connect("localhost", "root", "", "crud_app");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil nilai pencarian dari AJAX
$search = isset($_GET['search']) ? $_GET['search'] : "";
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;

// Query untuk pencarian
if ($search != "") {
    $query = "SELECT * FROM karyawan WHERE 
              nama_karyawan LIKE '%$search%' 
              OR nomor_induk LIKE '%$search%' 
              OR jabatan LIKE '%$search%'
              OR email LIKE '%$search%'
              OR no_telepon LIKE '%$search%'
              ORDER BY nama_karyawan ASC";
} else {
    $query = "SELECT * FROM karyawan LIMIT $start, $per_page";
}

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $no = $start + 1;
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
    echo "<tr><td colspan='8' class='no-results'>Tidak ada data ditemukan</td></tr>";
}

mysqli_close($conn);
?>
