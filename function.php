<?php
// Koneksi ke database
function connectDB() {
    $conn = mysqli_connect("localhost", "root", "", "crud_app");
    
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }
    
    return $conn;
}

// Fungsi untuk sanitasi input
function sanitizeInput($data) {
    $conn = connectDB();
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

// Fungsi untuk cek apakah nomor induk sudah terdaftar
function checkNomorInduk($nomor_induk) {
    $conn = connectDB();
    $nomor_induk = sanitizeInput($nomor_induk);
    
    $query_check = "SELECT * FROM users WHERE nomor_induk = '$nomor_induk'";
    $result_check = mysqli_query($conn, $query_check);
    
    $exists = mysqli_num_rows($result_check) > 0;
    mysqli_close($conn);
    
    return $exists;
}

// Fungsi untuk registrasi user baru
function registerUser($nomor_induk, $username, $password, $phone) {
    $conn = connectDB();
    
    // Sanitasi input
    $nomor_induk = sanitizeInput($nomor_induk);
    $username = sanitizeInput($username);
    $password = sanitizeInput($password);
    $phone = sanitizeInput($phone);
    
    // Query untuk insert data
    $query = "INSERT INTO users (nomor_induk, username, password, phone) 
              VALUES ('$nomor_induk', '$username', '$password', '$phone')";
    
    $result = mysqli_query($conn, $query);
    $error = mysqli_error($conn);
    
    mysqli_close($conn);
    
    if ($result) {
        return true;
    } else {
        return $error;
    }
}

// Fungsi untuk cek login
function checkLogin($nomor_induk, $password) {
    $conn = connectDB();
    
    // Sanitasi input
    $nomor_induk = sanitizeInput($nomor_induk);
    $password = sanitizeInput($password);
    
    // Cek apakah nomor induk ada di database
    $query = "SELECT * FROM users WHERE nomor_induk = '$nomor_induk' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    
    $is_valid = mysqli_num_rows($result) == 1;
    
    mysqli_close($conn);
    
    return $is_valid;
}

// Fungsi untuk set session
function setLoginSession($nomor_induk) {
    $_SESSION['nomor_induk'] = $nomor_induk;
}

// Fungsi untuk cek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['nomor_induk']);
}

// Fungsi untuk logout
function logout() {
    session_start();
    session_unset();
    session_destroy();
}

// Fungsi untuk redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Fungsi untuk cek user login
function checkUserLogin() {
    if (!isset($_SESSION['nomor_induk'])) {
        redirect('index.php');
    }
}

// Fungsi untuk mendapatkan data user
function getUserData($nomor_induk) {
    $conn = connectDB();
    $nomor_induk = sanitizeInput($nomor_induk);
    
    $user_query = "SELECT username FROM users WHERE nomor_induk = '$nomor_induk'";
    $user_result = mysqli_query($conn, $user_query);
    $user_data = mysqli_fetch_assoc($user_result);
    
    mysqli_close($conn);
    return $user_data;
}

// Fungsi untuk menghapus data karyawan
function deleteKaryawan($nomor_induk_karyawan) {
    $conn = connectDB();
    $nomor_induk_karyawan = sanitizeInput($nomor_induk_karyawan);
    
    $delete_query = "DELETE FROM karyawan WHERE nomor_induk = '$nomor_induk_karyawan'";
    $result = mysqli_query($conn, $delete_query);
    
    $success = $result;
    $error = mysqli_error($conn);
    
    mysqli_close($conn);
    
    if ($success) {
        return true;
    } else {
        return $error;
    }
}

// Fungsi untuk menghitung total data karyawan
function countKaryawan() {
    $conn = connectDB();
    
    $total_query = "SELECT COUNT(*) as total FROM karyawan";
    $total_result = mysqli_query($conn, $total_query);
    $total_row = mysqli_fetch_assoc($total_result);
    $total_data = $total_row['total'];
    
    mysqli_close($conn);
    return $total_data;
}

// Fungsi untuk mengambil data karyawan dengan pagination
function getKaryawanWithPagination($start, $per_page) {
    $conn = connectDB();
    
    $query = "SELECT * FROM karyawan LIMIT $start, $per_page";
    $result = mysqli_query($conn, $query);
    
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    mysqli_close($conn);
    return $data;
}

// Fungsi untuk mencari data karyawan
function searchKaryawan($keyword) {
    $conn = connectDB();
    $keyword = sanitizeInput($keyword);
    
    $query = "SELECT * FROM karyawan WHERE 
              nomor_induk LIKE '%$keyword%' OR 
              nama LIKE '%$keyword%' OR 
              email LIKE '%$keyword%' OR 
              jabatan LIKE '%$keyword%'";
    
    $result = mysqli_query($conn, $query);
    
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    mysqli_close($conn);
    return $data;
}
?>