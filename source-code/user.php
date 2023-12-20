<?php
// Oturumu başlat
session_start();

// Kullanıcı oturum açmamışsa, giriş sayfasına git
if (!isset($_SESSION['logged-in'])) {
    die(header('Location: login.php'));
}

// Veritabanı bağlantısını ve sınıfını içe aktar
require('db-config.php');

// Oturumu açık olan kullanıcının verilerini al
$user_data = $db->Select(
    "SELECT *
        FROM `users`
            WHERE `telegram_id` = :id",
    [
        'id' => $_SESSION['telegram_id']
    ]
);

// Temiz değişkenleri kullanıcı verileriyle tanımla
$firstName        = $user_data[0]['first_name'];
$lastName         = $user_data[0]['last_name'];
$profilePicture   = $user_data[0]['profile_picture'];
$telegramID       = $user_data[0]['telegram_id'];
$telegramUsername = $user_data[0]['telegram_username'];
$userID           = $user_data[0]['id'];

/*
Telegram uygulamasında,
last name | profile picture | Telegram username
isteğe bağlıdır, bu nedenle bu verileri koşulla görüntülüyorum,
Veritabanımda bu isteğe bağlı veriler için NULL değerini kullanıyorum.
*/

/* ------------------------- */
/* HTML'DE KULLANICI VERİSİNİ GÖSTER */
/* ------------------------- */
if (!is_null($lastName)) {
    // Adı ve soyadı göster
    $HTML = "<h1>Merhaba, {$firstName} {$lastName}!</h1>";
} else {
    // Sadece adı göster
    $HTML = "<h1>Merhaba, {$firstName}!</h1>";
}

if (!is_null($profilePicture)) {
    // Profil resmini "image.jpg?v=time()" hilesi olmadan göster
    $HTML .= '
    <a href="' . $profilePicture . '" target="_blank">
        <img class="profile-picture" src="' . $profilePicture . '?v=' . time() . '">
    </a>
    ';
}

if (!is_null($lastName)) {
    // Adı ve soyadı göster
    $HTML .= '
    <h2 class="user-data">Ad: ' . $firstName . '</h2>
    <h2 class="user-data">Soyad: ' . $lastName . '</h2>
    ';
} else {
    // Sadece adı göster
    $HTML .= '<h2 class="user-data">Ad: ' . $firstName . '</h2>';
}

if (!is_null($telegramUsername)) {
    // Telegram kullanıcı adını göster
    $HTML .= '
    <h2 class="user-data">
        Kullanıcı Adı:
        <a href="https://t.me/' . $telegramUsername . '" target="_blank">
            @' . $telegramUsername . '
        </a>
    </h2>
    ';
}

// Telegram ID | Kullanıcı ID | Çıkış Butonunu göster
$HTML .= '
<h2 class="user-data">Telegram ID: ' . $telegramID . '</h2>
<h2 class="user-data">Kullanıcı ID: ' . $userID . '</h2>
<a href="logout.php"><h2 class="logout">Çıkış</h2></a>
';

// Tüm seçilen kullanıcı verilerini göster
# echo '<style>body { background-color: #000 !important; } .middle-center { display: none !important; }</style>';
# echo '<pre>', print_r($user_data, TRUE), '</pre>';
# echo '<pre>', print_r($_SESSION, TRUE), '</pre>';
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <title>Oturum Açan Kullanıcı</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nanum+Gothic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="middle-center">
        <?= $HTML ?>
    </div>
</body>

</html>
