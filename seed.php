<?php
// Jalankan sekali: php seed.php  (setelah schema.sql diimport)
require __DIR__ . '/classes/Database.php';
$pdo = (new Database())->getConnection();

$kelas = [['X PPLG 1','PPLG'],['X PPLG 2','PPLG'],['XI PPLG 1','PPLG'],['XII PPLG 1','PPLG'],['X TJKT 1','TJKT']];
$st = $pdo->prepare("INSERT IGNORE INTO kelas (nama_kelas, jurusan) VALUES (?, ?)");
foreach ($kelas as $k) $st->execute($k);

$st = $pdo->prepare("INSERT IGNORE INTO users (username, password) VALUES (?, ?)");
$st->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT)]);

echo "Seeder selesai. Login: admin / admin123\n";
