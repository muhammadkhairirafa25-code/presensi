<?php
require 'includes/init.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
    (new Presensi($pdo, (int) $_POST['id']))->delete();
    flash('success', 'Log presensi dihapus.');
    redirect('rekap.php?' . http_build_query([
        'tanggal' => $_POST['tanggal'] ?? '', 'id_kelas' => $_POST['id_kelas'] ?? '', 'q' => $_POST['q'] ?? ''
    ]));
}

$tanggal = $_GET['tanggal'] ?? date('Y-m-d');          // bawaan: hari ini
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) $tanggal = date('Y-m-d');
$idKelas = $_GET['id_kelas'] ?? '';
$q = trim($_GET['q'] ?? '');

$data = (new Presensi($pdo))->getAll($tanggal, $idKelas, $q);
$kelasList = (new Kelas($pdo))->getAll();
$judul = 'Rekap Presensi';
require 'includes/header.php';
?>
<div class="card">
    <h2>Rekap Presensi</h2>
    <form method="get" class="inline">
        <div><label>Tanggal</label><input type="date" name="tanggal" value="<?= e($tanggal) ?>"></div>
        <div><label>Kelas</label>
            <select name="id_kelas">
                <option value="">Semua kelas</option>
                <?php foreach ($kelasList as $k): ?>
                <option value="<?= e($k['id_kelas']) ?>" <?= $idKelas == $k['id_kelas'] ? 'selected' : '' ?>><?= e($k['nama_kelas']) ?></option>
                <?php endforeach; ?>
            </select></div>
        <div><label>Nama / NISN</label><input type="text" name="q" value="<?= e($q) ?>"></div>
        <div><button class="btn" type="submit">Filter</button> <a class="btn gray" href="rekap.php">Hari ini</a></div>
    </form>
    <p>Total hadir: <b><?= count($data) ?></b></p>
    <div class="table-wrap">
    <table>
        <tr><th>Nama</th><th>NISN</th><th>Kelas</th><th>Tanggal &amp; Waktu</th><th>Aksi</th></tr>
        <?php foreach ($data as $r): ?>
        <tr>
            <td><?= e($r['nama']) ?></td>
            <td><?= e($r['nisn']) ?></td>
            <td><?= e($r['nama_kelas']) ?></td>
            <td><?= e($r['tanggal_waktu']) ?></td>
            <td>
                <form class="d" method="post" onsubmit="return confirm('Hapus log ini?')">
                    <input type="hidden" name="id" value="<?= e($r['id']) ?>">
                    <input type="hidden" name="tanggal" value="<?= e($tanggal) ?>">
                    <input type="hidden" name="id_kelas" value="<?= e($idKelas) ?>">
                    <input type="hidden" name="q" value="<?= e($q) ?>">
                    <button class="btn danger" name="hapus" value="1">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$data): ?><tr><td colspan="5">Belum ada presensi.</td></tr><?php endif; ?>
    </table>
    </div>
</div>
<?php require 'includes/footer.php'; ?>
