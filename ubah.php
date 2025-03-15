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

$error_message = "";
$success_message = "";

// Get employee ID from URL
if (!isset($_GET['id'])) {
    header("Location: lihat.php");
    exit();
}

$nomor_induk = $_GET['id'];

// Fetch current employee data
$query = "SELECT * FROM karyawan WHERE nomor_induk = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $nomor_induk);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: lihat.php");
    exit();
}

$employee = mysqli_fetch_assoc($result);

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $nama_karyawan = $_POST['nama_karyawan'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telepon'];
    $jabatan = $_POST['jabatan'];
    
    // Validate input
    $is_valid = true;
    
    if (empty($nama_karyawan)) {
        $error_message = "Nama karyawan tidak boleh kosong";
        $is_valid = false;
    } elseif (empty($email)) {
        $error_message = "Email tidak boleh kosong";
        $is_valid = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Format email tidak valid";
        $is_valid = false;
    } elseif (empty($no_telepon)) {
        $error_message = "Nomor telepon tidak boleh kosong";
        $is_valid = false;
    } elseif (!is_numeric($no_telepon)) {
        $error_message = "Nomor telepon harus berupa angka";
        $is_valid = false;
    } elseif (empty($jabatan)) {
        $error_message = "Jabatan tidak boleh kosong";
        $is_valid = false;
    }
    
    // If data is valid, update in database
    if ($is_valid) {
        // Cek apakah nomor induk baru sudah ada di database
        $nomor_induk_baru = $_POST['nomor_induk'];
        if ($nomor_induk_baru !== $nomor_induk) {
            $check_query = "SELECT nomor_induk FROM karyawan WHERE nomor_induk = ?";
            $stmt_check = mysqli_prepare($conn, $check_query);
            mysqli_stmt_bind_param($stmt_check, "s", $nomor_induk_baru);
            mysqli_stmt_execute($stmt_check);
            $result_check = mysqli_stmt_get_result($stmt_check);
    
            if (mysqli_num_rows($result_check) > 0) {
                $error_message = "Nomor Induk sudah digunakan oleh karyawan lain!";
                $is_valid = false;
            }
        }
    
        if ($is_valid) {
            $update_query = "UPDATE karyawan SET 
                            nomor_induk = ?, 
                            nama_karyawan = ?, 
                            jenis_kelamin = ?, 
                            email = ?, 
                            no_telepon = ?, 
                            jabatan = ? 
                            WHERE nomor_induk = ?";
    
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, "sssssss", $nomor_induk_baru, $nama_karyawan, $jenis_kelamin, $email, $no_telepon, $jabatan, $nomor_induk);
    
            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Data karyawan berhasil diperbarui!";
                $nomor_induk = $nomor_induk_baru; // Update nomor induk di sesi edit
            } else {
                $error_message = "Gagal memperbarui data: " . mysqli_error($conn);
            }
        }
        if (!empty($success_message)) {
            // Ambil data terbaru setelah update
            $query = "SELECT * FROM karyawan WHERE nomor_induk = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $nomor_induk_baru);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $employee = mysqli_fetch_assoc($result);
        }
        
    }
    
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Karyawan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="text-primary text-center">Edit Data Karyawan</h2>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
            <div class="mb-3">
    <label for="nomor_induk" class="form-label">Nomor Induk:</label>
    <input type="text" id="nomor_induk" name="nomor_induk" class="form-control" value="<?php echo $employee['nomor_induk']; ?>" required>
    <small class="text-muted">Ubah nomor induk jika diperlukan</small>
</div>

                
                <div class="mb-3">
                    <label for="nama_karyawan" class="form-label">Nama Karyawan:</label>
                    <input type="text" id="nama_karyawan" name="nama_karyawan" class="form-control" value="<?php echo $employee['nama_karyawan']; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin:</label>
                    <div class="form-check">
                        <input type="radio" id="laki" name="jenis_kelamin" class="form-check-input" value="Laki-laki" <?php echo ($employee['jenis_kelamin'] == 'Laki-laki') ? 'checked' : ''; ?>>
                        <label for="laki" class="form-check-label">Laki-laki</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" id="perempuan" name="jenis_kelamin" class="form-check-input" value="Perempuan" <?php echo ($employee['jenis_kelamin'] == 'Perempuan') ? 'checked' : ''; ?>>
                        <label for="perempuan" class="form-check-label">Perempuan</label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $employee['email']; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="no_telepon" class="form-label">No. Telepon:</label>
                    <input type="text" id="no_telepon" name="no_telepon" class="form-control" value="<?php echo $employee['no_telepon']; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="jabatan" class="form-label">Jabatan:</label>
                    <input type="text" id="jabatan" name="jabatan" class="form-control" value="<?php echo $employee['jabatan']; ?>" required>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="lihat.php" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php mysqli_close($conn); ?>