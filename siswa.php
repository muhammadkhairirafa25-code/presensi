<?php
require 'includes/init.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
    try {
        (new Siswa($pdo, $_POST['nisn']))->delete();
        flash('success', 'Siswa berhasil dihapus.');
    } catch (PDOException $ex) {
        // 23000 = pelanggaran foreign key (siswa masih punya log presensi)
        flash('error', 'Siswa tidak dapat dihapus karena masih memiliki data presensi.');
    }
    redirect('siswa.php');
}

$q = trim($_GET['q'] ?? '');
$data = (new Siswa($pdo))->getAll($q);
$judul = 'Siswa';
require 'includes/header.php';
?>
<div class="card">
    <h2>Data Siswa</h2>
    <form method="get" class="inline">
        <div><input type="text" name="q" placeholder="Cari nama atau NISN" value="<?= e($q) ?>"></div>
        <div><button class="btn" type="submit">Cari</button>
        <a class="btn gray" href="siswa.php">Reset</a>
        <a class="btn" href="siswa_form.php">+ Tambah</a></div>
    </form>
    <div class="table-wrap" style="margin-top:12px">
    <table>
        <tr><th>NISN</th><th>Nama</th><th>Alamat</th><th>Kelas</th><th>Aksi</th></tr>
        <?php foreach ($data as $s): ?>
        <tr>
            <td><?= e($s['nisn']) ?></td>
            <td><?= e($s['nama']) ?></td>
            <td><?= e($s['alamat']) ?></td>
            <td><?= e($s['nama_kelas']) ?></td>
            <td>
                <a class="btn gray" href="siswa_form.php?nisn=<?= e($s['nisn']) ?>">Ubah</a>
                <form class="d" method="post" onsubmit="return confirm('Hapus siswa ini?')">
                    <input type="hidden" name="nisn" value="<?= e($s['nisn']) ?>">
                    <button class="btn danger" name="hapus" value="1">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$data): ?><tr><td colspan="5">Data tidak ditemukan.</td></tr><?php endif; ?>
    </table>
    </div>
</div>
<?php require 'includes/footer.php'; ?>
