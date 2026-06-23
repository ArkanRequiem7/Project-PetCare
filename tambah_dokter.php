<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Resepsionis') {
    header("Location: index.php");
    exit;
}

$pesan_sukses = '';
$pesan_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_dokter  = mysqli_real_escape_string($conn, $_POST['nama_dokter']);
    $spesialisasi = mysqli_real_escape_string($conn, $_POST['spesialisasi']);
    
    $hari         = mysqli_real_escape_string($conn, $_POST['hari']);
    $jam_mulai    = mysqli_real_escape_string($conn, $_POST['jam_mulai']);
    $jam_selesai  = mysqli_real_escape_string($conn, $_POST['jam_selesai']);
    $kuota_harian = (int) $_POST['kuota_harian'];

    if (!empty($nama_dokter) && !empty($spesialisasi) && !empty($hari) && !empty($jam_mulai) && !empty($jam_selesai) && !empty($kuota_harian)) {
        
        $query_dokter = "INSERT INTO dokter (nama_dokter, spesialisasi) VALUES ('$nama_dokter', '$spesialisasi')";
        
        if (mysqli_query($conn, $query_dokter)) {
            $id_dokter = mysqli_insert_id($conn);
            
            $query_jadwal = "INSERT INTO jadwal_dokter (id_dokter, hari, jam_mulai, jam_selesai, kuota_harian) 
                             VALUES ('$id_dokter', '$hari', '$jam_mulai', '$jam_selesai', '$kuota_harian')";
                             
            if (mysqli_query($conn, $query_jadwal)) {
                $pesan_sukses = "Data dokter <b>$nama_dokter</b> beserta jadwalnya berhasil ditambahkan!";
            } else {
                mysqli_query($conn, "DELETE FROM dokter WHERE id_dokter = '$id_dokter'");
                $pesan_error = "Gagal menambahkan jadwal: " . mysqli_error($conn);
            }
        } else {
            $pesan_error = "Gagal menambahkan data dokter: " . mysqli_error($conn);
        }
    } else {
        $pesan_error = "Semua kolom wajib diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dokter & Jadwal - Pet Care</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex flex-col">

    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="index.php" class="flex items-center">
                        <i class="fa-solid fa-paw text-indigo-600 text-3xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900 tracking-wide">Pet<span class="text-indigo-600">Care</span></span>
                    </a>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="index.php" class="text-gray-600 hover:text-indigo-600 font-medium transition flex items-center">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Beranda
                    </a>
                    <div class="border-l pl-6 border-gray-200">
                        <span class="text-sm font-semibold text-gray-700 flex items-center">
                            <i class="fa-solid fa-user-shield text-indigo-600 text-xl mr-2"></i>
                            Admin Panel
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl shadow-xl border border-gray-100 relative overflow-hidden">
            
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-50 rounded-full z-0"></div>
            
            <div class="text-center relative z-10">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 text-indigo-600 mb-4 shadow-sm">
                    <i class="fa-solid fa-user-doctor text-2xl"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900">Tambah Dokter & Jadwal</h2>
                <p class="mt-2 text-sm text-gray-500">Masukkan profil dokter beserta jadwal praktiknya.</p>
            </div>

            <?php if ($pesan_sukses): ?>
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg relative z-10">
                    <div class="flex">
                        <div class="flex-shrink-0"><i class="fa-solid fa-circle-check text-emerald-500"></i></div>
                        <div class="ml-3"><p class="text-sm text-emerald-700 font-medium"><?= $pesan_sukses ?></p></div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($pesan_error): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg relative z-10">
                    <div class="flex">
                        <div class="flex-shrink-0"><i class="fa-solid fa-triangle-exclamation text-red-500"></i></div>
                        <div class="ml-3"><p class="text-sm text-red-700 font-medium"><?= $pesan_error ?></p></div>
                    </div>
                </div>
            <?php endif; ?>

            <form class="mt-8 space-y-6 relative z-10" action="" method="POST">
                
                <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200 space-y-5">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2 border-b pb-2"><i class="fa-solid fa-user mr-1"></i> Profil Dokter</h3>
                    <div>
                        <label for="nama_dokter" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap Dokter</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-id-card text-gray-400"></i>
                            </div>
                            <input id="nama_dokter" name="nama_dokter" type="text" required class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition shadow-sm" placeholder="Contoh: drh. Budi Santoso">
                        </div>
                    </div>

                    <div>
                        <label for="spesialisasi" class="block text-sm font-medium text-gray-700 mb-1">Spesialisasi / Keahlian</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-stethoscope text-gray-400"></i>
                            </div>
                            <input id="spesialisasi" name="spesialisasi" type="text" required class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition shadow-sm" placeholder="Contoh: Dokter Umum, Bedah...">
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200 space-y-5">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2 border-b pb-2"><i class="fa-solid fa-calendar-days mr-1"></i> Jadwal Praktik</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="hari" class="block text-sm font-medium text-gray-700 mb-1">Hari Praktik</label>
                            <select id="hari" name="hari" required class="block w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition shadow-sm bg-white">
                                <option value="" disabled selected>Pilih Hari</option>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                                <option value="Minggu">Minggu</option>
                            </select>
                        </div>
                        <div>
                            <label for="kuota_harian" class="block text-sm font-medium text-gray-700 mb-1">Kuota Harian (Pasien)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-users text-gray-400"></i>
                                </div>
                                <input id="kuota_harian" name="kuota_harian" type="number" min="1" required class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition shadow-sm" placeholder="Contoh: 15">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                            <input id="jam_mulai" name="jam_mulai" type="time" required class="block w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition shadow-sm bg-white">
                        </div>
                        <div>
                            <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                            <input id="jam_selesai" name="jam_selesai" type="time" required class="block w-full px-3 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition shadow-sm bg-white">
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        <i class="fa-solid fa-save mr-2 mt-0.5"></i> Simpan Dokter & Jadwal
                    </button>
                </div>
            </form>
        </div>
    </main>

    <footer class="bg-gray-900 text-gray-400 py-8 border-t border-gray-800 text-sm mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center flex flex-col sm:flex-row justify-between items-center">
            <div class="flex items-center text-white mb-4 sm:mb-0">
                <i class="fa-solid fa-paw text-indigo-500 text-xl mr-2"></i>
                <span class="text-base font-bold tracking-wide">Pet<span class="text-indigo-500">Care</span> Admin</span>
            </div>
            <p>&copy; <?= date('Y'); ?> Pet Care Smart Clinic System.</p>
        </div>
    </footer>

</body>
</html>