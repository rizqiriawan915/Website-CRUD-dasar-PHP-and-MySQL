<?php
session_start();

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "crud_app");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Cek apakah form login telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomor_induk = $_POST['nomorInduk'];
    $password = $_POST['password'];

    // Cek apakah nomor induk ada di database
    $query = "SELECT * FROM users WHERE nomor_induk = '$nomor_induk' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // Login berhasil
        $_SESSION['nomor_induk'] = $nomor_induk;
        header("Location: lihat.php"); // Redirect ke halaman utama
        exit();
    } else {
        echo "Nomor Induk atau Password salah!";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/index.css">
    <title>Login</title>
</head>
<body>
    <form method="POST">
        <h2>Halaman Login</h2>
        <label for="nomorInduk">Masukkan nomor induk anda:</label>
        <input type="text" id="nomorInduk" name="nomorInduk" required autocomplete="off"><br>

        <label for="password">Masukkan password anda:</label>
        <input type="password" id="password" name="password" required autocomplete="off"><br>

        <a href="registration.php">Belum mempunyai akun? Silahkan daftar terlebih dahulu</a><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
