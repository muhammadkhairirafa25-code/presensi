<?php
require 'includes/init.php';
require_login();

$id = (int) ($_GET['id'] ?? 0);
$model = new Kelas($pdo);
$nama = $jurusan = '';
$errors = [];

if ($id) {
    $row = $model->find($id);
    if (!$row) { flash('error', 'Kelas tidak ditemukan.'); redirect('kelas.php'); }
    $nama = $row['nama_kelas'];
    $jurusan = $row['jurusan'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_kelas'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');

    if ($nama === '')                          $errors['nama_kelas'] = 'Nama kelas wajib diisi.';
    elseif (strlen($nama) > 20)                $errors['nama_kelas'] = 'Nama kelas maksimal 20 karakter.';
    elseif ($model->namaExists($nama, $id))    $errors['nama_kelas'] = 'Nama kelas sudah ada.';
    if ($jurusan === '')                       $errors['jurusan'] = 'Jurusan wajib diisi.';
    elseif (strlen($jurusan) > 50)             $errors['jurusan'] = 'Jurusan maksimal 50 karakter.';

    if (!$errors) {
        $k = new Kelas($pdo, $id ?: null, $nama, $jurusan);
        $id ? $k->update() : $k->create();
        flash('success', 'Kelas berhasil disimpan.');
        redirect('kelas.php');
    }
}
$judul = $id ? 'Ubah Kelas' : 'Tambah Kelas';
require 'includes/header.php';
?>
<div class="card">
    <h2><?= e($judul) ?></h2>
    <form method="post">
        <label>Nama Kelas</label>
        <input type="text" name="nama_kelas" value="<?= e($nama) ?>">
        <?php if (isset($errors['nama_kelas'])): ?><div class="err"><?= e($errors['nama_kelas']) ?></div><?php endif; ?>
        <label>Jurusan</label>
        <input type="text" name="jurusan" value="<?= e($jurusan) ?>">
        <?php if (isset($errors['jurusan'])): ?><div class="err"><?= e($errors['jurusan']) ?></div><?php endif; ?>
        <p><button class="btn" type="submit">Simpan</button> <a class="btn gray" href="kelas.php">Batal</a></p>
    </form>
</div>
<?php require 'includes/footer.php'; ?>
