<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
$id_user_login = $_SESSION['id_user'];
$pesan_sukses = '';
$pesan_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $role === 'Resepsionis' && isset($_POST['update_status'])) {
    $id_antrean = (int)$_POST['id_antrean'];
    $status_baru = mysqli_real_escape_string($conn, $_POST['status']);
    
    $query_update = "UPDATE antrean SET status = '$status_baru' WHERE id_antrean = $id_antrean";
    if (mysqli_query($conn, $query_update)) {
        $pesan_sukses = "Status antrean berhasil diperbarui!";
    } else {
        $pesan_error = "Gagal memperbarui status: " . mysqli_error($conn);
    }
}

$query_antrean = "
    SELECT a.*, u.nama_lengkap AS nama_pasien, d.nama_dokter, j.jam_mulai, j.jam_selesai 
    FROM antrean a
    JOIN user u ON a.id_user = u.id_user
    JOIN jadwal_dokter j ON a.id_jadwal = j.id_jadwal
    JOIN dokter d ON j.id_dokter = d.id_dokter
    ORDER BY a.tanggal_berobat DESC, a.no_antrean ASC
";
$result_antrean = mysqli_query($conn, $query_antrean);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Antrean - Pet Care</title>
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
                            <i class="fa-solid fa-circle-user text-indigo-600 text-xl mr-2"></i>
                            <?= htmlspecialchars($_SESSION['nama_lengkap']); ?> 
                            (<?= $role ?>)
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900"><i class="fa-solid fa-list-ol text-indigo-600 mr-2"></i> Monitor Antrean</h1>
                <p class="text-gray-500 mt-2">Pantau status antrean pasien dan jadwal dokter secara real-time.</p>
            </div>
            
            <?php if ($role === 'Pasien'): ?>
            <div class="mt-4 sm:mt-0">
                <a href="ambil_antrean.php" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-indigo-700 transition shadow-md">
                    <i class="fa-solid fa-ticket mr-2"></i> Ambil Antrean Baru
                </a>
            </div>
            <?php endif; ?>
        </div>

        <?php if ($pesan_sukses): ?>
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-lg">
                <div class="flex items-center">
                    <i class="fa-solid fa-circle-check text-emerald-500 mr-3"></i>
                    <p class="text-sm text-emerald-700 font-medium"><?= $pesan_sukses ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($pesan_error): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                <div class="flex items-center">
                    <i class="fa-solid fa-triangle-exclamation text-red-500 mr-3"></i>
                    <p class="text-sm text-red-700 font-medium"><?= $pesan_error ?></p>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No. Antrean</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl & Waktu</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pasien</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dokter</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <?php if ($role === 'Resepsionis'): ?>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi Admin</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (mysqli_num_rows($result_antrean) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result_antrean)): 
                                $badge_color = 'bg-gray-100 text-gray-800';
                                if ($row['status'] == 'Menunggu') $badge_color = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                                if ($row['status'] == 'Dipanggil') $badge_color = 'bg-blue-100 text-blue-800 border-blue-200';
                                if ($row['status'] == 'Selesai') $badge_color = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                                if ($row['status'] == 'Batal') $badge_color = 'bg-red-100 text-red-800 border-red-200';
                                
                                $row_bg = ($role === 'Pasien' && $row['id_user'] == $id_user_login) ? 'bg-indigo-50/50' : 'hover:bg-gray-50';
                            ?>
                            <tr class="<?= $row_bg ?> transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 font-bold text-lg">
                                        <?= htmlspecialchars($row['no_antrean']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900"><?= date('d M Y', strtotime($row['tanggal_berobat'])) ?></div>
                                    <div class="text-xs text-gray-500 mt-1"><i class="fa-regular fa-clock"></i> <?= substr($row['jam_mulai'], 0, 5) ?> - <?= substr($row['jam_selesai'], 0, 5) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($row['nama_pasien']) ?>
                                        <?php if ($role === 'Pasien' && $row['id_user'] == $id_user_login): ?>
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">Anda</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><i class="fa-solid fa-stethoscope text-gray-400 mr-1"></i> <?= htmlspecialchars($row['nama_dokter']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border <?= $badge_color ?>">
                                        <?= $row['status'] ?>
                                    </span>
                                </td>
                                
                                <?php if ($role === 'Resepsionis'): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <form action="" method="POST" class="flex items-center space-x-2">
                                        <input type="hidden" name="id_antrean" value="<?= $row['id_antrean'] ?>">
                                        <select name="status" class="block w-32 pl-3 pr-8 py-1.5 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm">
                                            <option value="Menunggu" <?= ($row['status'] == 'Menunggu') ? 'selected' : '' ?>>Menunggu</option>
                                            <option value="Dipanggil" <?= ($row['status'] == 'Dipanggil') ? 'selected' : '' ?>>Dipanggil</option>
                                            <option value="Selesai" <?= ($row['status'] == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
                                            <option value="Batal" <?= ($row['status'] == 'Batal') ? 'selected' : '' ?>>Batal</option>
                                        </select>
                                        <button type="submit" name="update_status" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white px-3 py-1.5 rounded-md border border-indigo-200 hover:border-transparent transition text-xs font-bold">
                                            Update
                                        </button>
                                    </form>
                                </td>
                                <?php endif; ?>
                                
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= ($role === 'Resepsionis') ? '6' : '5' ?>" class="px-6 py-10 text-center text-gray-500">
                                    <i class="fa-regular fa-folder-open text-4xl mb-3 block text-gray-300"></i>
                                    Belum ada data antrean yang terdaftar.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="bg-gray-900 text-gray-400 py-6 border-t border-gray-800 text-sm mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; <?= date('Y'); ?> Pet Care Smart Clinic System. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>