<?php
require_once 'koneksi.php';

$pesan_error = '';
$pesan_sukses = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password_mentah = $_POST['password'];
    $role = $_POST['role']; 

    $cek_username = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");
    
    if (mysqli_num_rows($cek_username) > 0) {
        $pesan_error = "Username sudah terdaftar. Silakan gunakan username lain.";
    } else {
        $password_hash = password_hash($password_mentah, PASSWORD_DEFAULT);

        $query = "INSERT INTO user (username, password, nama_lengkap, no_telp, role) 
                  VALUES ('$username', '$password_hash', '$nama_lengkap', '$no_telp', '$role')";

        if (mysqli_query($conn, $query)) {
            $pesan_sukses = "Pendaftaran berhasil! Silakan login.";
        } else {
            $pesan_error = "Terjadi kesalahan sistem: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Pet Care Smart Clinic</title>
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
        <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-xl w-full max-w-lg border border-gray-100">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-extrabold text-gray-900">Buat Akun Baru</h2>
                <p class="text-gray-500 mt-2 text-sm">Bergabunglah untuk kemudahan merawat hewan kesayangan Anda.</p>
            </div>

            <?php if ($pesan_sukses): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0"><i class="fa-solid fa-circle-check text-green-500"></i></div>
                        <div class="ml-3"><p class="text-sm text-green-700 font-medium"><?= $pesan_sukses ?></p></div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($pesan_error): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0"><i class="fa-solid fa-triangle-exclamation text-red-500"></i></div>
                        <div class="ml-3"><p class="text-sm text-red-700 font-medium"><?= $pesan_error ?></p></div>
                    </div>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-5">
                <input type="hidden" name="role" value="Pasien">

                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-address-card text-gray-400"></i>
                        </div>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" required class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition shadow-sm placeholder-gray-400" placeholder="Nama Lengkap Anda">
                    </div>
                </div>

                <div>
                    <label for="no_telp" class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp/Telepon</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-phone text-gray-400"></i>
                        </div>
                        <input type="tel" id="no_telp" name="no_telp" required class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition shadow-sm placeholder-gray-400" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-user text-gray-400"></i>
                        </div>
                        <input type="text" id="username" name="username" required class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition shadow-sm placeholder-gray-400" placeholder="Buat username (Tanpa spasi)">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="password" name="password" required class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition shadow-sm placeholder-gray-400" placeholder="Minimal 8 karakter">
                    </div>
                </div>

                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-200 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition mt-6">
                    <i class="fa-solid fa-user-plus mr-2 mt-0.5"></i> Daftar Akun
                </button>
            </form>

            <div class="mt-6 text-center text-sm">
                <p class="text-gray-600">Sudah memiliki akun? 
                    <a href="login.php" class="font-bold text-indigo-600 hover:text-indigo-500 transition">Login sekarang</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>