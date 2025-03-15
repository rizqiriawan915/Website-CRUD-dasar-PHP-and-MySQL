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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow p-4">
                    <h2 class="text-center text-success">Halaman Registrasi</h2>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="nomorInduk" class="form-label">Masukkan nomor induk anda:</label>
                            <input type="text" id="nomorInduk" name="nomorInduk" class="form-control" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Masukkan nama anda:</label>
                            <input type="text" id="nama" name="nama" class="form-control" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Masukkan password anda:</label>
                            <input type="password" id="password" name="password" class="form-control" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="nomorTelepon" class="form-label">Masukkan nomor telepon anda:</label>
                            <input type="text" id="nomorTelepon" name="nomorTelepon" class="form-control" required autocomplete="off">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Daftar</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <a href="index.php">Sudah mempunyai akun? Silahkan login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

