<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Digital Library</title>
    
    <link rel="stylesheet" href="style.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

    <div class="login-container">
        <div class="login-box register-box">
            <form action="auth_register.php" method="post">
                <h2>Buat Akun Baru</h2>
                <p class="subtitle">Silakan daftar untuk membuat akun</p>

                <?php
                // Tampilkan pesan error jika ada dari auth_register.php
                if (isset($_GET['error'])) {
                    $message = '';
                    if ($_GET['error'] == 'empty') {
                        $message = 'Semua field harus diisi!';
                    } elseif ($_GET['error'] == 'password') {
                        $message = 'Password dan konfirmasi password tidak sama!';
                    } elseif ($_GET['error'] == 'userexist') {
                        $message = 'Username sudah digunakan!';
                    }
                    echo '<div class="error-message">' . htmlspecialchars($message) . '</div>';
                }
                
                // Tampilkan pesan sukses
                if (isset($_GET['success'])) {
                    echo '<div class="success-message">Registrasi berhasil! Silakan <a href="login.php">login</a>.</div>';
                }
                ?>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-user-tag"></i>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
                </div>

                <button type="submit" name="submit" class="login-btn">Daftar</button>

                <div class="register-link">
                    <p>Sudah punya akun? <a href="login.php">Login</a></p>
                </div>
            </form>
        </div>
    </div>

</body>
</html> 