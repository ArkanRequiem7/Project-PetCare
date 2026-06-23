<?php
if (!isset($conn)) {
    require_once 'koneksi.php';
}

$query_jadwal = "SELECT jd.hari, jd.jam_mulai, jd.jam_selesai, jd.kuota_harian, 
                        d.nama_dokter, d.spesialisasi 
                 FROM jadwal_dokter jd
                 JOIN dokter d ON jd.id_dokter = d.id_dokter
                 ORDER BY FIELD(jd.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), jd.jam_mulai ASC";

$result_jadwal = mysqli_query($conn, $query_jadwal);
?>

<section id="jadwal" class="bg-white py-20 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Jadwal Praktik Dokter Hewan</h2>
            <p class="text-gray-600 mt-2">Data di bawah ini diambil secara langsung dari sistem database klinik.</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-700 font-semibold text-sm uppercase tracking-wider">
                            <th class="px-6 py-4">Nama Dokter</th>
                            <th class="px-6 py-4">Spesialisasi</th>
                            <th class="px-6 py-4">Hari Praktik</th>
                            <th class="px-6 py-4">Jam Kerja</th>
                            <th class="px-6 py-4 text-center">Kuota Maksimal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-600 text-sm">
                        <?php 
                        if (mysqli_num_rows($result_jadwal) > 0) {
                            while($row = mysqli_fetch_assoc($result_jadwal)) {
                                $jam_mulai   = substr($row['jam_mulai'], 0, 5);
                                $jam_selesai = substr($row['jam_selesai'], 0, 5);
                        ?>
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="px-6 py-4 font-medium text-gray-900 flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold mr-3">drh</div>
                                        <?= htmlspecialchars($row['nama_dokter']); ?>
                                    </td>
                                    <td class="px-6 py-4"><?= htmlspecialchars($row['spesialisasi']); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-2.5 py-1 rounded-md">
                                            <?= htmlspecialchars($row['hari']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4"><?= $jam_mulai . " - " . $jam_selesai; ?> WIB</td>
                                    <td class="px-6 py-4 text-center font-semibold text-indigo-600"><?= $row['kuota_harian']; ?> Pasien</td>
                                </tr>
                        <?php 
                            }
                        } else { 
                        ?>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                    <i class="fa-solid fa-calendar-xmark text-2xl mb-2 block"></i>
                                    Belum ada jadwal dokter yang terdaftar di database.
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>