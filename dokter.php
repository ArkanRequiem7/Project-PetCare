<?php
if (!isset($conn)) {
    require_once 'koneksi.php';
}

$query_dokter = "SELECT * FROM dokter ORDER BY nama_dokter ASC";
$result_dokter = mysqli_query($conn, $query_dokter);
?>

<section id="dokter" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t border-gray-100">
    <div class="text-center max-w-3xl mx-auto mb-12">
        <h2 class="text-3xl font-bold text-gray-900">Tim Dokter Hewan Kami</h2>
        <p class="text-gray-600 mt-2">Dapatkan pelayanan terbaik dari dokter spesialis veteriner yang berpengalaman.</p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php 
        if (mysqli_num_rows($result_dokter) > 0) {
            while($doc = mysqli_fetch_assoc($result_dokter)) {
        ?>
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center space-x-4 hover:shadow-md transition">
                    <div class="w-14 h-14 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xl shrink-0">
                        drh
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg"><?= htmlspecialchars($doc['nama_dokter']); ?></h3>
                        <p class="text-sm text-gray-500 font-medium"><?= htmlspecialchars($doc['spesialisasi']); ?></p>
                    </div>
                </div>
        <?php 
            }
        } else {
        ?>
            <div class="col-span-full text-center text-gray-400 py-4">
                Belum ada data dokter terdaftar.
            </div>
        <?php } ?>
    </div>
</section>