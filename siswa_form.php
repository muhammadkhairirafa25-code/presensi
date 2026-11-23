<?php
require 'includes/init.php';
require_login();

$nisnGet = $_GET['nisn'] ?? '';
$edit = $nisnGet !== '';
$kelasList = (new Kelas($pdo))->getAll();
$v = ['nisn' => '', 'nama' => '', 'alamat' => '', 'id_kelas' => ''];
$errors = [];

if ($edit && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $row = (new Siswa($pdo))->find($nisnGet);
    if (!$row) { flash('error', 'Siswa tidak ditemukan.'); redirect('siswa.php'); }
    $v = array_merge($v, $row);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $v['nisn']     = $edit ? $nisnGet : trim($_POST['nisn'] ?? '');
    $v['nama']     = trim($_POST['nama'] ?? '');
    $v['alamat']   = trim($_POST['alamat'] ?? '');
    $v['id_kelas'] = $_POST['id_kelas'] ?? '';

    $s = new Siswa($pdo, $v['nisn'], $v['nama'], $v['alamat'], $v['id_kelas']);
    $errors = $s->validate($edit);
    if (!$errors) {
        $edit ? $s->update() : $s->create();
        flash('success', 'Data siswa berhasil disimpan.');
        redirect('siswa.php');
    }
}
$judul = $edit ? 'Ubah Siswa' : 'Tambah Siswa';
require 'includes/header.php';

function pesan($errors, $k) {
    return isset($errors[$k]) ? '<div class="err">' . e($errors[$k]) . '</div>' : '';
}
?>
<div class="card">
    <h2><?= e($judul) ?></h2>
    <form method="post">
        <label>NISN (10 digit)</label>
        <input type="text" name="nisn" maxlength="20" value="<?= e($v['nisn']) ?>" <?= $edit ? 'readonly' : '' ?>>
        <?= pesan($errors, 'nisn') ?>
        <label>Nama</label>
        <input type="text" name="nama" value="<?= e($v['nama']) ?>">
        <?= pesan($errors, 'nama') ?>
        <label>Alamat (boleh kosong)</label>
        <textarea name="alamat" rows="3"><?= e($v['alamat']) ?></textarea>
        <?= pesan($errors, 'alamat') ?>
        <label>Kelas</label>
        <select name="id_kelas">
            <option value="">-- Pilih kelas --</option>
            <?php foreach ($kelasList as $k): ?>
            <option value="<?= e($k['id_kelas']) ?>" <?= (string) $v['id_kelas'] === (string) $k['id_kelas'] ? 'selected' : '' ?>>
                <?= e($k['nama_kelas']) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <?= pesan($errors, 'id_kelas') ?>
        <p><button class="btn" type="submit">Simpan</button> <a class="btn gray" href="siswa.php">Batal</a></p>
    </form>
</div>
<?php require 'includes/footer.php'; ?>
