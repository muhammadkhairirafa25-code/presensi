<?php
session_start();

// Autoload: class dimuat otomatis dari folder classes/
spl_autoload_register(function ($class) {
    require __DIR__ . '/../classes/' . $class . '.php';
});

// Mengamankan output (XSS)
function e($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }

function redirect($url) { header("Location: $url"); exit; }

// Pesan sekali tampil (flash message)
function flash($type, $msg) { $_SESSION['flash'] = [$type, $msg]; }

// Proteksi halaman
function require_login() {
    if (empty($_SESSION['id_user'])) redirect('login.php');
}

$pdo = (new Database())->getConnection();
