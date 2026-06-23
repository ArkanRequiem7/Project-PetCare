<?php
session_start();
require_once 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care - Smart Vet Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fa-solid fa-paw text-indigo-600 text-3xl mr-2"></i>
                    <span class="text-xl font-bold text-gray-900 tracking-wide">Pet<span class="text-indigo-600">Care</span></span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#hero" class="text-gray-600 hover:text-indigo-600 font-medium transition">Beranda</a>
                    <a href="#fitur" class="text-gray-600 hover:text-indigo-600 font-medium transition">Layanan</a>
                    <a href="#dokter" class="text-gray-600 hover:text-indigo-600 font-medium transition">Dokter</a>
                    <a href="#jadwal" class="text-gray-600 hover:text-indigo-600 font-medium transition">Jadwal</a>
                    
                    <?php if (isset($_SESSION['id_user'])): ?>
                        <a href="lihat_antrean.php" class="text-indigo-600 hover:text-indigo-800 font-bold transition flex items-center bg-indigo-50 px-3 py-1.5 rounded-xl">
                            <i class="fa-solid fa-desktop mr-1.5"></i> Monitor Antrean
                        </a>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Resepsionis'): ?>
                        <a href="tambah_dokter.php" class="text-indigo-600 hover:text-indigo-800 font-bold transition flex items-center">
                            <i class="fa-solid fa-user-doctor mr-1"></i> Tambah Dokter
                        </a>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['nama_lengkap'])): ?>
                        <div class="flex items-center space-x-4 border-l pl-6 border-gray-200">
                            <span class="text-sm font-semibold text-gray-700 flex items-center">
                                <i class="fa-solid fa-circle-user text-indigo-600 text-xl mr-2"></i>
                                Halo, <?= htmlspecialchars($_SESSION['nama_lengkap']); ?>
                                <?php if($_SESSION['role'] == 'Resepsionis'): ?>
                                    <span class="ml-2 bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-0.5 rounded">Admin</span>
                                <?php endif; ?>
                            </span>
                            <a href="logout.php" class="text-sm text-red-500 hover:text-red-700 font-medium transition px-3 py-1.5 rounded-md hover:bg-red-50">
                                <i class="fa-solid fa-arrow-right-from-bracket mr-1"></i> Keluar
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-indigo-700 transition shadow-sm">
                            <i class="fa-solid fa-right-to-bracket mr-2"></i>Login Portal
                        </a>
                    <?php endif; ?>
                </div>
                <div class="flex items-center md:hidden">
                    <button class="text-gray-600 hover:text-indigo-600 focus:outline-none">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <section id="hero" class="bg-gradient-to-br from-indigo-50 to-cyan-50 py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <span class="bg-indigo-100 text-indigo-800 text-sm font-semibold px-3 py-1 rounded-full uppercase tracking-wider">Smart Clinic Queue</span>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 leading-tight">
                    Klinik Digital, <br><span class="text-indigo-600">Tanpa Antre Berjam-jam!</span>
                </h1>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Ubah cara lama merawat hewan peliharaan Anda. Ambil nomor antrean secara online, pantau status panggilan secara real-time, dan akses rekam medis peliharaan Anda dalam satu aplikasi terintegrasi.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 pt-2">
                    <a href="ambil_antrean.php" class="bg-indigo-600 text-white text-center px-6 py-3 rounded-xl font-semibold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 flex items-center justify-center">
                        <i class="fa-solid fa-ticket mr-2"></i> Ambil Nomor Antrean
                    </a>
                    <a href="#jadwal" class="border border-gray-300 bg-white text-center text-gray-700 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition flex items-center justify-center">
                        Cek Jadwal Dokter
                    </a>
                </div>
            </div>
            <div class="flex justify-center">
                <div class="relative w-full max-w-md aspect-square bg-indigo-600 rounded-3xl overflow-hidden shadow-2xl flex items-center justify-center text-white">
                    <div class="absolute inset-0 bg-opacity-20 bg-cover bg-center" style="background-image: url('https://i.postimg.cc/BZPsLbGh/1920-adobestock-731101015.jpg');"></div>
                    <div class="relative z-10 text-center p-6 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent h-full w-full flex flex-col justify-end items-start rounded-3xl">
                        <p class="text-sm font-medium tracking-wide text-indigo-300 uppercase">Kesehatan Hewan Prioritas Kami</p>
                        <h3 class="text-2xl font-bold mt-1 text-left">Pelayanan Veteriner Berbasis Teknologi</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20">
        <div class="bg-white rounded-2xl shadow-xl grid grid-cols-3 divide-x divide-gray-100 p-6 text-center">
            <div>
                <span class="block text-2xl sm:text-3xl font-bold text-indigo-600">01</span>
                <span class="text-xs sm:text-sm text-gray-500 font-medium">Ambil Antrean</span>
            </div>
            <div>
                <span class="block text-2xl sm:text-3xl font-bold text-indigo-600">02</span>
                <span class="text-xs sm:text-sm text-gray-500 font-medium">Datang & Periksa</span>
            </div>
            <div>
                <span class="block text-2xl sm:text-3xl font-bold text-indigo-600">03</span>
                <span class="text-xs sm:text-sm text-gray-500 font-medium">Selesai & Rekam Medis</span>
            </div>
        </div>
    </section>

    <section id="fitur" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl font-bold text-gray-900">Layanan Digital Pet Care</h2>
            <p class="text-gray-600 mt-2">Didesain khusus untuk mempermudah manajemen klinik dan kenyamanan pemilik hewan (Pet Parent).</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center text-xl font-bold mb-6">
                    <i class="fa-solid fa-users-line"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Sistem Antrean Cerdas</h3>
                <p class="text-gray-600 leading-relaxed">Pasien (hewan) mendapatkan nomor antrean digital otomatis berdasarkan dokter pilihan dan kuota harian yang tersedia.</p>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 bg-cyan-100 text-cyan-600 rounded-xl flex items-center justify-center text-xl font-bold mb-6">
                    <i class="fa-solid fa-notes-medical"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Rekam Medis Elektronik</h3>
                <p class="text-gray-600 leading-relaxed">Catatan riwayat kesehatan hewan, berat badan, suhu tubuh, diagnosa penyakit, hingga resep obat tersimpan rapi dan aman.</p>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl font-bold mb-6">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Laporan & Analisis</h3>
                <p class="text-gray-600 leading-relaxed">Kemudahan manajemen klinik dalam memantau statistik kunjungan bulanan serta performa kerja para dokter hewan.</p>
            </div>
        </div>
    </section>

    <?php 
    if(file_exists('dokter.php')) include 'dokter.php'; 
    if(file_exists('jadwal.php')) include 'jadwal.php'; 
    ?>
    
    <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800 text-sm mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center text-white">
                <i class="fa-solid fa-paw text-indigo-500 text-2xl mr-2"></i>
                <span class="text-lg font-bold tracking-wide">Pet<span class="text-indigo-500">Care</span></span>
            </div>
            <p>&copy; <?= date('Y'); ?> Pet Care Smart Clinic System. All rights reserved.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-white transition"><i class="fa-brands fa-instagram text-lg"></i></a>
                <a href="#" class="hover:text-white transition"><i class="fa-brands fa-whatsapp text-lg"></i></a>
                <a href="#" class="hover:text-white transition"><i class="fa-solid fa-envelope text-lg"></i></a>
            </div>
        </div>
    </footer>

</body>
</html>

<?php
mysqli_close($conn);
?>