<?php
// Include file function.php
require_once 'function.php';

$message = '';
$message_type = '';

// Cek apakah tombol submit ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomor_induk = $_POST['nomorInduk'];
    $username = $_POST['nama'];
    $password = $_POST['password'];
    $phone = $_POST['nomorTelepon'];

    // Cek apakah nomor induk sudah digunakan
    if (checkNomorInduk($nomor_induk)) {
        $message = "Nomor Induk sudah terdaftar. Silakan gunakan yang lain.";
        $message_type = "danger";
    } else {
        // Registrasi user baru
        $result = registerUser($nomor_induk, $username, $password, $phone);
        
        if ($result === true) {
            $message = "Registrasi berhasil! <a href='index.php'>Login sekarang</a>";
            $message_type = "success";
        } else {
            $message = "Error: " . $result;
            $message_type = "danger";
        }
    }
}
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
                    
                    <?php if (!empty($message)): ?>
                    <div class="alert alert-<?php echo $message_type; ?>" role="alert">
                        <?php echo $message; ?>
                    </div>
                    <?php endif; ?>
                    
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