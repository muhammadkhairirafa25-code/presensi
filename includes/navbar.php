<nav class="navbar">
    <span class="brand">Presensi SMK TI Airlangga</span>
    <?php if (!empty($_SESSION['id_user'])): ?>
    <div class="links">
        <a href="dashboard.php">Dashboard</a>
        <a href="rekap.php">Rekap</a>
        <a href="tap.php">Tap Kartu</a>
        <a href="siswa.php">Siswa</a>
        <a href="kelas.php">Kelas</a>
        <a href="logout.php">Logout (<?= e($_SESSION['username']) ?>)</a>
    </div>
    <?php endif; ?>
</nav>
