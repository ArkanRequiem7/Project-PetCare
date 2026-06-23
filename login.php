<?php
session_start();

require_once 'koneksi.php';

$pesan_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password_mentah = $_POST['password'];

    $query = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password_mentah, $row['password']) || $password_mentah === $row['password']) {
            
            $_SESSION['id_user']      = $row['id_user'];
            $_SESSION['username']     = $row['username'];
            $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
            $_SESSION['role']         = $row['role'];

            if ($row['role'] == 'Resepsionis') {
                header("Location: index.php"); 
            } else {
                header("Location: index.php"); 
            }
            exit;
        } else {
            $pesan_error = "Username atau password salah!";
        }
    } else {
        $pesan_error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pet Care Smart Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-indigo-50 to-cyan-50 text-gray-800 font-sans min-h-screen flex flex-col">

    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center h-16 items-center">
                <a href="index.php" class="flex items-center">
                    <i class="fa-solid fa-paw text-indigo-600 text-3xl mr-2"></i>
                    <span class="text-xl font-bold text-gray-900 tracking-wide">Pet<span class="text-indigo-600">Care</span></span>
                </a>
            </div>
        </div>
    </nav>

    <div class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-xl w-full max-w-md border border-gray-100">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900">Selamat Datang!</h2>
                <p class="text-gray-500 mt-2 text-sm">Masuk untuk mengakses rekam medis dan antrean peliharaan Anda.</p>
            </div>

            <?php if ($pesan_error): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium"><?= $pesan_error ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-user text-gray-400"></i>
                        </div>
                        <input type="text" id="username" name="username" required class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition shadow-sm placeholder-gray-400" placeholder="Masukkan username">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="password" name="password" required class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition shadow-sm placeholder-gray-400" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                    </div>
                </div>

                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-200 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    <i class="fa-solid fa-right-to-bracket mr-2 mt-0.5"></i> Masuk Sekarang
                </button>
            </form>

            <div class="mt-6 text-center text-sm">
                <p class="text-gray-600">Belum punya akun? 
                    <a href="register.php" class="font-bold text-indigo-600 hover:text-indigo-500 transition">Daftar di sini</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>