<?php
session_start(); 

require_once 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}
$id_user_login = $_SESSION['id_user']; 

$query_jadwal = "SELECT jd.id_jadwal, jd.hari, jd.jam_mulai, jd.jam_selesai, jd.kuota_harian,
                        d.nama_dokter, d.spesialisasi 
                 FROM jadwal_dokter jd 
                 JOIN dokter d ON jd.id_dokter = d.id_dokter
                 ORDER BY FIELD(jd.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), jd.jam_mulai ASC";
$result_jadwal = mysqli_query($conn, $query_jadwal);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_jadwal       = mysqli_real_escape_string($conn, $_POST['id_jadwal']);
    $tanggal_berobat = mysqli_real_escape_string($conn, $_POST['tanggal_berobat']);
    $status          = 'Menunggu'; 

    if (!empty($id_jadwal) && !empty($tanggal_berobat)) {
        
        $timestamp    = strtotime($tanggal_berobat);
        $daftar_hari  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $hari_pilihan = $daftar_hari[date('w', $timestamp)];

        $query_cek_hari = "SELECT hari, kuota_harian FROM jadwal_dokter WHERE id_jadwal = '$id_jadwal'";
        $res_cek_hari   = mysqli_query($conn, $query_cek_hari);
        $data_jadwal    = mysqli_fetch_assoc($res_cek_hari);

        if ($data_jadwal['hari'] !== $hari_pilihan) {
            $error_pesan = "Tanggal yang Anda pilih adalah hari <b>$hari_pilihan</b>, sedangkan dokter hanya praktik pada hari <b>".$data_jadwal['hari']."</b>.";
        } else {

            $query_kuota = "SELECT COUNT(id_antrean) AS total 
                            FROM antrean 
                            WHERE id_jadwal = '$id_jadwal' 
                            AND tanggal_berobat = '$tanggal_berobat' 
                            AND status != 'Batal'";
            $res_kuota   = mysqli_query($conn, $query_kuota);
            $data_kuota  = mysqli_fetch_assoc($res_kuota);
            
            $total_pendaftar = (int) $data_kuota['total'];
            
            if ($total_pendaftar >= (int) $data_jadwal['kuota_harian']) {
                $error_pesan = "Maaf, kuota antrean dokter untuk tanggal tersebut sudah penuh (Maksimal ".$data_jadwal['kuota_harian']." pasien).";
            } else {

                $query_max = "SELECT no_antrean 
                              FROM antrean 
                              WHERE id_jadwal = '$id_jadwal' 
                              AND tanggal_berobat = '$tanggal_berobat' 
                              ORDER BY no_antrean DESC LIMIT 1";
                $res_max   = mysqli_query($conn, $query_max);
                
                if (mysqli_num_rows($res_max) > 0) {
                    $row_max = mysqli_fetch_assoc($res_max);
                    $no_antrean_baru = (int) $row_max['no_antrean'] + 1;
                } else {
                    $no_antrean_baru = 1;
                }

                $query_insert = "INSERT INTO antrean (id_user, id_jadwal, tanggal_berobat, no_antrean, status) 
                                 VALUES ('$id_user_login', '$id_jadwal', '$tanggal_berobat', '$no_antrean_baru', '$status')";
                
                if (mysqli_query($conn, $query_insert)) {
                    $sukses_pesan = "Antrean berhasil diambil!<br>Nomor Antrean Anda: <span class='text-xl font-bold text-indigo-600'>#" . $no_antrean_baru . "</span>";
                } else {
                    $error_pesan = "Gagal memproses antrean: " . mysqli_error($conn);
                }
            }
        }
    } else {
        $error_pesan = "Semua formulir wajib diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Antrean - Pet Care</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-indigo-50 to-cyan-50 min-h-screen font-sans flex flex-col justify-between">

    <nav class="bg-white shadow-sm py-4 px-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="index.php" class="flex items-center text-xl font-bold text-gray-900 tracking-wide">
                <i class="fa-solid fa-paw text-indigo-600 text-2xl mr-2"></i>
                Pet<span class="text-indigo-600">Care</span>
            </a>
            <div class="flex items-center space-x-4">
                <span class="text-sm font-semibold text-gray-700 bg-gray-100 px-3 py-1 rounded-full">
                    <i class="fa-solid fa-user text-indigo-500 mr-1"></i> <?= htmlspecialchars($_SESSION['nama_lengkap']); ?>
                </span>
                <a href="index.php" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-xl w-full mx-auto p-4 my-8 flex-grow flex items-center justify-center">
        <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 w-full">
            
            <div class="text-center mb-8">
                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xl mx-auto mb-3">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Pendaftaran Antrean</h1>
                <p class="text-sm text-gray-500 mt-1">Silakan tentukan jadwal dokter dan tanggal kunjungan berobat.</p>
            </div>

            <?php if (!empty($sukses_pesan)): ?>
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-start space-x-3 text-sm">
                    <i class="fa-solid fa-circle-check text-lg text-emerald-500 mt-0.5"></i>
                    <div><?= $sukses_pesan; ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_pesan)): ?>
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-start space-x-3 text-sm">
                    <i class="fa-solid fa-circle-exclamation text-lg text-rose-500 mt-0.5"></i>
                    <div><?= $error_pesan; ?></div>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-6">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="id_jadwal">
                        <i class="fa-solid fa-user-doctor mr-1 text-gray-400"></i> Pilih Dokter & Jadwal Praktik
                    </label>
                    <select name="id_jadwal" id="id_jadwal" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white text-gray-800 transition text-sm">
                        <option value="">-- Pilih Dokter & Hari Kerja --</option>
                        <?php 
                        if (mysqli_num_rows($result_jadwal) > 0) {
                            while($jadwal = mysqli_fetch_assoc($result_jadwal)) {
                                $jam_kerja = substr($jadwal['jam_mulai'], 0, 5) . " - " . substr($jadwal['jam_selesai'], 0, 5);
                                echo "<option value='".$jadwal['id_jadwal']."'>drh. ".htmlspecialchars($jadwal['nama_dokter'])." (".$jadwal['spesialisasi'].") [".$jadwal['hari']." | ".$jam_kerja." WIB]</option>";
                            }
                        } else {
                            echo "<option value='' disabled>Belum ada jadwal dokter yang terdaftar.</option>";
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="tanggal_berobat">
                        <i class="fa-solid fa-calendar-day mr-1 text-gray-400"></i> Tanggal Rencana Berobat
                    </label>
                    <input type="date" name="tanggal_berobat" id="tanggal_berobat" required
                        min="<?= date('Y-m-d'); ?>"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white text-gray-800 transition text-sm">
                    <p class="text-xs text-gray-400 mt-1.5">*Pastikan hari pada tanggal yang Anda pilih sama dengan hari operasional praktik dokter.</p>
                </div>

                <button type="submit" 
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-indigo-100 transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-address-card"></i>
                    <span>Ambil Nomor Antrean</span>
                </button>

            </form>

        </div>
    </main>

    <footer class="text-center py-6 text-xs text-gray-400 border-t border-gray-200/50 bg-white">
        &copy; <?= date('Y') ?> Pet Care Smart Clinic System. All rights reserved.
    </footer>

</body>
</html>