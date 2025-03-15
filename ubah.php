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
        $update_query = "UPDATE karyawan SET 
                        nama_karyawan = ?, 
                        jenis_kelamin = ?, 
                        email = ?, 
                        no_telepon = ?, 
                        jabatan = ? 
                        WHERE nomor_induk = ?";
                        
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "ssssss", $nama_karyawan, $jenis_kelamin, $email, $no_telepon, $jabatan, $nomor_induk);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Data karyawan berhasil diperbarui!";
            
            // Refresh employee data after successful update
            $query = "SELECT * FROM karyawan WHERE nomor_induk = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $nomor_induk);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $employee = mysqli_fetch_assoc($result);
        } else {
            $error_message = "Gagal memperbarui data: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/ubah.css">
    <title>Edit Data Karyawan</title>
</head>
<body>
    <div class="container">
        <h2>Edit Data Karyawan</h2>
        
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
            <div class="form-group">
                <label for="nomor_induk">Nomor Induk:</label>
                <input type="text" id="nomor_induk" name="nomor_induk" value="<?php echo $employee['nomor_induk']; ?>" disabled>
                <p class="help-text">Nomor induk tidak dapat diubah</p>
            </div>
            
            <div class="form-group">
                <label for="nama_karyawan">Nama Karyawan:</label>
                <input type="text" id="nama_karyawan" name="nama_karyawan" value="<?php echo $employee['nama_karyawan']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Jenis Kelamin:</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="jenis_kelamin" value="Laki-laki" <?php echo ($employee['jenis_kelamin'] == 'Laki-laki') ? 'checked' : ''; ?>>
                        Laki-laki
                    </label>
                    <label>
                        <input type="radio" name="jenis_kelamin" value="Perempuan" <?php echo ($employee['jenis_kelamin'] == 'Perempuan') ? 'checked' : ''; ?>>
                        Perempuan
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $employee['email']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="no_telepon">No. Telepon:</label>
                <input type="text" id="no_telepon" name="no_telepon" value="<?php echo $employee['no_telepon']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="jabatan">Jabatan:</label>
                <input type="text" id="jabatan" name="jabatan" value="<?php echo $employee['jabatan']; ?>" required>
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn btn-save">Simpan Perubahan</button>
                <a href="lihat.php" class="btn btn-cancel">Kembali</a>
            </div>
        </form>
    </div>
    
    <script>
        // Form validation on client-side
        document.querySelector('form').addEventListener('submit', function(e) {
            const nama = document.getElementById('nama_karyawan').value.trim();
            const email = document.getElementById('email').value.trim();
            const telepon = document.getElementById('no_telepon').value.trim();
            const jabatan = document.getElementById('jabatan').value.trim();
            
            if (nama === '') {
                alert('Nama karyawan tidak boleh kosong');
                e.preventDefault();
                return false;
            }
            
            if (email === '') {
                alert('Email tidak boleh kosong');
                e.preventDefault();
                return false;
            }
            
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert('Format email tidak valid');
                e.preventDefault();
                return false;
            }
            
            if (telepon === '') {
                alert('Nomor telepon tidak boleh kosong');
                e.preventDefault();
                return false;
            }
            
            if (isNaN(telepon)) {
                alert('Nomor telepon harus berupa angka');
                e.preventDefault();
                return false;
            }
            
            if (jabatan === '') {
                alert('Jabatan tidak boleh kosong');
                e.preventDefault();
                return false;
            }
        });
    </script>
</body>
</html>

<?php mysqli_close($conn); ?>