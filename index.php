<?php
session_start();
// Include file function.php
require_once 'function.php';

// Cek jika user sudah login, redirect ke halaman utama
if (isLoggedIn()) {
    redirect('lihat.php');
}

$error_message = '';

// Cek apakah form login telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomor_induk = $_POST['nomorInduk'];
    $password = $_POST['password'];

    // Cek login
    if (checkLogin($nomor_induk, $password)) {
        // Login berhasil
        setLoginSession($nomor_induk);
        redirect('lihat.php'); // Redirect ke halaman utama
    } else {
        $error_message = "Nomor Induk atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow p-4">
                    <h2 class="text-center text-primary">Halaman Login</h2>
                    
                    <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="nomorInduk" class="form-label">Masukkan nomor induk anda:</label>
                            <input type="text" id="nomorInduk" name="nomorInduk" class="form-control" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Masukkan password anda:</label>
                            <input type="password" id="password" name="password" class="form-control" required autocomplete="off">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <a href="registration.php">Belum mempunyai akun? Silahkan daftar terlebih dahulu</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>