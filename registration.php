<?php
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "crud_app");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Cek apakah tombol submit ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomor_induk = $_POST['nomorInduk'];
    $username = $_POST['nama'];
    $password = $_POST['password'];
    $phone = $_POST['nomorTelepon'];

    // Cek apakah nomor induk sudah digunakan
    $query_check = "SELECT * FROM users WHERE nomor_induk = '$nomor_induk'";
    $result_check = mysqli_query($conn, $query_check);

    if (mysqli_num_rows($result_check) > 0) {
        echo "Nomor Induk sudah terdaftar. Silakan gunakan yang lain.";
    } else {
        // Query untuk insert data
        $query = "INSERT INTO users (nomor_induk, username, password, phone) 
                  VALUES ('$nomor_induk', '$username', '$password', '$phone')";

        if (mysqli_query($conn, $query)) {
            echo "Registrasi berhasil! <a href='index.php'>Login sekarang</a>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
</head>
<body>
    <h2>Halaman Registrasi</h2>
    <form method="POST">
        <label for="nomorInduk">Masukkan nomor induk anda:</label>
        <input type="text" id="nomorInduk" name="nomorInduk" required autocomplete="off"><br>

        <label for="nama">Masukkan nama anda:</label>
        <input type="text" id="nama" name="nama" required autocomplete="off"><br>

        <label for="password">Masukkan password anda:</label>
        <input type="password" id="password" name="password" required autocomplete="off"><br>

        <label for="nomorTelepon">Masukkan nomor telepon anda:</label>
        <input type="text" id="nomorTelepon" name="nomorTelepon" required autocomplete="off"><br>

        <a href="index.php">Sudah mempunyai akun? Silahkan login</a><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
