<?php
// Tüm oturumları sonlandır ve giriş sayfasına git
session_start();
session_unset();
session_destroy();
die(header('Location: login.php'));
