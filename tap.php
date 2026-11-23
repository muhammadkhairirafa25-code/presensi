<?php
require 'includes/init.php';
require_login();

$nisn = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn = trim($_POST['nisn'] ?? '');
    $siswa = preg_match('/^\d{10}$/', $nisn) ? (new Siswa($pdo))->find($nisn) : false;
    if (!$siswa) {
        flash('error', 'NISN tidak terdaftar. Pastikan kartu valid atau daftarkan siswa terlebih dahulu.');
    } else {
        (new Presensi($pdo, null, $nisn))->create();   // waktu diisi database
        flash('success', 'Presensi tercatat untuk ' . $siswa['nama'] . '.');
        redirect('tap.php');
    }
    redirect('tap.php');
}
$judul = 'Tap Kartu';
require 'includes/header.php';
?>
<div class="card" style="max-width:450px;margin:auto">
    <h2>Simulasi Tap Kartu RFID</h2>
    <form method="post">
        <label>NISN</label>
        <input type="text" name="nisn" maxlength="10" autofocus required>
        <p><button class="btn" type="submit">Tap</button> <a class="btn gray" href="rekap.php">Lihat Rekap</a></p>
    </form>
</div>
<?php require 'includes/footer.php'; ?>
