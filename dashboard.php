<?php
require 'includes/init.php';
require_login();

$hariIni = date('Y-m-d');
$presensi = new Presensi($pdo);

$totalSiswa = (new Siswa($pdo))->countAll();
$totalKelas = count((new Kelas($pdo))->getAll());
$hadir      = $presensi->countHadir($hariIni);
$tidakHadir = $totalSiswa - $hadir;           // tidak tap = tidak hadir
$persen     = $totalSiswa > 0 ? round($hadir / $totalSiswa * 100) : 0;
$perKelas   = $presensi->rekapPerKelas($hariIni);
$terbaru    = $presensi->terbaru($hariIni, 5);

$judul = 'Dashboard';
require 'includes/header.php';
?>
<h2>Dashboard - <?= e(date('d-m-Y')) ?></h2>

<div class="stats">
    <div class="stat"><div class="n"><?= $totalSiswa ?></div><div class="l">Total Siswa</div></div>
    <div class="stat"><div class="n"><?= $totalKelas ?></div><div class="l">Total Kelas</div></div>
    <div class="stat ok"><div class="n"><?= $hadir ?></div><div class="l">Hadir Hari Ini (<?= $persen ?>%)</div></div>
    <div class="stat no"><div class="n"><?= $tidakHadir ?></div><div class="l">Belum Hadir</div></div>
</div>

<div class="card">
    <h3>Kehadiran per Kelas (hari ini)</h3>
    <div class="table-wrap">
    <table>
        <tr><th>Kelas</th><th>Hadir</th><th>Total</th><th>Persentase</th></tr>
        <?php foreach ($perKelas as $r):
            $p = $r['total'] > 0 ? round($r['hadir'] / $r['total'] * 100) : 0; ?>
        <tr>
            <td><?= e($r['nama_kelas']) ?></td>
            <td><?= e($r['hadir']) ?></td>
            <td><?= e($r['total']) ?></td>
            <td><div class="bar"><div style="width:<?= $p ?>%"></div></div> <?= $p ?>%</td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$perKelas): ?><tr><td colspan="4">Belum ada kelas.</td></tr><?php endif; ?>
    </table>
    </div>
</div>

<div class="card">
    <h3>Tap Terbaru</h3>
    <div class="table-wrap">
    <table>
        <tr><th>Nama</th><th>Kelas</th><th>Waktu</th></tr>
        <?php foreach ($terbaru as $r): ?>
        <tr>
            <td><?= e($r['nama']) ?></td>
            <td><?= e($r['nama_kelas']) ?></td>
            <td><?= e(date('H:i:s', strtotime($r['tanggal_waktu']))) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$terbaru): ?><tr><td colspan="3">Belum ada presensi hari ini.</td></tr><?php endif; ?>
    </table>
    </div>
    <p><a class="btn" href="tap.php">Simulasi Tap</a> <a class="btn gray" href="rekap.php">Lihat Rekap Lengkap</a></p>
</div>
<?php require 'includes/footer.php'; ?>
