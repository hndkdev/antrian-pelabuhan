<?php
session_start();
session_destroy();
header('Location: login-petugas.php');
exit;
