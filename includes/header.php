<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($judul ?? 'Presensi Siswa') ?> - Presensi Siswa</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php require __DIR__ . '/navbar.php'; ?>
<main class="container">
<?php if (!empty($_SESSION['flash'])): [$t, $m] = $_SESSION['flash']; unset($_SESSION['flash']); ?>
    <div class="alert <?= e($t) ?>"><?= e($m) ?></div>
<?php endif; ?>
