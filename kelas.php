<?php
require 'includes/init.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
    $kelas = new Kelas($pdo, (int) $_POST['id']);
    if ($kelas->countSiswa() > 0) {
        flash('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa.');
    } else {
        $kelas->delete();
        flash('success', 'Kelas berhasil dihapus.');
    }
    redirect('kelas.php');
}

$data = (new Kelas($pdo))->getAll();
$judul = 'Kelas';
require 'includes/header.php';
?>
<div class="card">
    <h2>Data Kelas</h2>
    <a class="btn" href="kelas_form.php">+ Tambah Kelas</a>
    <div class="table-wrap" style="margin-top:12px">
    <table>
        <tr><th>No</th><th>Nama Kelas</th><th>Jurusan</th><th>Jumlah Siswa</th><th>Aksi</th></tr>
        <?php foreach ($data as $i => $k): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= e($k['nama_kelas']) ?></td>
            <td><?= e($k['jurusan']) ?></td>
            <td><?= e($k['jumlah_siswa']) ?></td>
            <td>
                <a class="btn gray" href="kelas_form.php?id=<?= e($k['id_kelas']) ?>">Ubah</a>
                <form class="d" method="post" onsubmit="return confirm('Hapus kelas ini?')">
                    <input type="hidden" name="id" value="<?= e($k['id_kelas']) ?>">
                    <button class="btn danger" name="hapus" value="1">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    </div>
</div>
<?php require 'includes/footer.php'; ?>
