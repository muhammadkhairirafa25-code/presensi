<?php
require 'includes/init.php';
if (!empty($_SESSION['id_user'])) redirect('rekap.php');

$error = '';
$username = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $user = (new User($pdo))->login($username, $password);
    if ($user) {
        session_regenerate_id(true);
        $_SESSION['id_user']  = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        redirect('rekap.php');
    }
    // Pesan umum: tidak menyebutkan bagian mana yang salah
    $error = 'Username atau password salah.';
}
$judul = 'Login';
require 'includes/header.php';
?>
<div class="card" style="max-width:400px;margin:auto">
    <h2>Login Administrator</h2>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
        <label>Username</label>
        <input type="text" name="username" value="<?= e($username) ?>" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <p><button class="btn" type="submit">Masuk</button></p>
    </form>
</div>
<?php require 'includes/footer.php'; ?>
