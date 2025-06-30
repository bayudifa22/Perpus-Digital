<?php
// Selalu mulai sesi di awal
session_start();

// Sertakan koneksi database
include "koneksi.php";

// Pastikan form disubmit
if (isset($_POST['submit'])) {

    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi dasar agar tidak kosong
    if (empty($nama_lengkap) || empty($username) || empty($password) || empty($confirm_password)) {
        header("Location: register.php?error=empty");
        exit();
    }

    // Validasi konfirmasi password
    if ($password !== $confirm_password) {
        header("Location: register.php?error=password");
        exit();
    }

    // Cek apakah username sudah ada
    $check_username = "SELECT * FROM users WHERE username = ?";
    $stmt_username = mysqli_prepare($db, $check_username);
    mysqli_stmt_bind_param($stmt_username, "s", $username);
    mysqli_stmt_execute($stmt_username);
    $result_username = mysqli_stmt_get_result($stmt_username);

    if (mysqli_num_rows($result_username) > 0) {
        header("Location: register.php?error=userexist");
        exit();
    }
    mysqli_stmt_close($stmt_username);

    // Split nama_lengkap into firstname and lastname
    $name_parts = explode(" ", $nama_lengkap, 2);
    $firstname = $name_parts[0];
    $lastname = isset($name_parts[1]) ? $name_parts[1] : "";

    // Hash password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan data user baru
    $insert_query = "INSERT INTO users (username, password, firstname, lastname) VALUES (?, ?, ?, ?)";
    
    if ($stmt_insert = mysqli_prepare($db, $insert_query)) {
        mysqli_stmt_bind_param($stmt_insert, "ssss", $username, $hashed_password, $firstname, $lastname);
        
        if (mysqli_stmt_execute($stmt_insert)) {
            // Registrasi berhasil, redirect ke login dengan pesan sukses
            header("Location: login.php?registration=success");
            exit();
        } else {
            // Terjadi kesalahan saat menyimpan data
            header("Location: register.php?error=dberror");
            exit();
        }
        mysqli_stmt_close($stmt_insert);
    }
    mysqli_close($db);

} else {
    // Jika file diakses langsung tanpa submit, kembalikan ke halaman register
    header("Location: register.php");
    exit();
}
?> 