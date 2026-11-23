<?php
require 'includes/init.php';
redirect(empty($_SESSION['id_user']) ? 'login.php' : 'rekap.php');
