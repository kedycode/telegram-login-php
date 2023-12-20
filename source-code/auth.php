<?php
// Oturumu başlat
session_start();

// Kullanıcı oturum açtıysa, kullanıcı sayfasına git
if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == TRUE) {
    die(header('Location: user.php'));
}

// Veritabanı bağlantısını ve sınıfını içe aktar
require('db-config.php');

// Botunuzun token'ını buraya yerleştirin
define('BOT_TOKEN', 'XXXXXXXXXXXX:XXXXXXXXXXXXXXXXXXXXXXXX');

// Telegram hash'i yetkilendirmek için gereklidir
if (!isset($_GET['hash'])) {
    die('Telegram hash bulunamadı');
}

// Resmi Telegram yetkilendirmesi - fonksiyon
function checkTelegramAuthorization($auth_data)
{
    $check_hash = $auth_data['hash'];
    unset($auth_data['hash']);
    $data_check_arr = [];
    foreach ($auth_data as $key => $value) {
        $data_check_arr[] = $key . '=' . $value;
    }
    sort($data_check_arr);
    $data_check_string = implode("\n", $data_check_arr);
    $secret_key = hash('sha256', BOT_TOKEN, true);
    $hash = hash_hmac('sha256', $data_check_string, $secret_key);
    if (strcmp($hash, $check_hash) !== 0) {
        throw new Exception('Veri Telegram\'dan DEĞİL');
    }
    if ((time() - $auth_data['auth_date']) > 86400) {
        throw new Exception('Veri güncel değil');
    }
    return $auth_data;
}

// Kullanıcı yetkilendirme - fonksiyon
function userAuthentication($db, $auth_data)
{
    // Yeni kullanıcı oluştur - fonksiyon
    function createNewUser($db, $auth_data)
    {
        // Kullanıcı bulunamadı, bu yüzden oluştur
        $id = $db->Insert(
            "INSERT INTO `users`
                (`first_name`, `last_name`, `telegram_id`, `telegram_username`, `profile_picture`, `auth_date`)
                    values (:first_name, :last_name, :telegram_id, :telegram_username, :profile_picture, :auth_date)",
            [
                'first_name'        => $auth_data['first_name'],
                'last_name'         => $auth_data['last_name'],
                'telegram_id'       => $auth_data['id'],
                'telegram_username' => $auth_data['username'],
                'profile_picture'   => $auth_data['photo_url'],
                'auth_date'         => $auth_data['auth_date']
            ]
        );
    }

    // Varolan kullanıcıyı güncelle - fonksiyon
    function updateExistedUser($db, $auth_data)
    {
        // Kullanıcı bulundu, bu yüzden güncelle
        $db->Update(
            "UPDATE `users`
                SET `first_name`        = :first_name,
                    `last_name`         = :last_name,
                    `telegram_username` = :telegram_username,
                    `profile_picture`   = :profile_picture,
                    `auth_date`         = :auth_date
                        WHERE `telegram_id` = :telegram_id",
            [
                'first_name'        => $auth_data['first_name'],
                'last_name'         => $auth_data['last_name'],
                'telegram_username' => $auth_data['username'],
                'profile_picture'   => $auth_data['photo_url'],
                'auth_date'         => $auth_data['auth_date'],
                'telegram_id'       => $auth_data['id']
            ]
        );
    }

    // Kullanıcı var mı diye kontrol et - fonksiyon
    function checkUserExists($db, $auth_data)
    {
        // Kullanıcı Telegram ID'sini al
        $target_id = $auth_data['id'];

        // Kullanıcının veritabanında olup olmadığını kontrol et
        $isUser = $db->Select(
            "SELECT `telegram_id`
                FROM `users`
                    WHERE `telegram_id` = :id",
            [
                'id' => $target_id
            ]
        );

        // Kullanıcı veritabanında varsa true döndür
        if (!empty($isUser) && $isUser[0]['telegram_id'] === $target_id) {
            return TRUE;
        }
    }

    // Kullanıcıyı kontrol et
    if (checkUserExists($db, $auth_data) == TRUE) {
        // Kullanıcı bulundu, bu yüzden güncelle
        updateExistedUser($db, $auth_data);
    } else {
        // Kullanıcı bulunamadı, bu yüzden oluştur
        createNewUser($db, $auth_data);
    }

    // Oturum açık olan kullanıcı oturumu oluştur
    $_SESSION = [
        'logged-in' => TRUE,
        'telegram_id' => $auth_data['id']
    ];
}

// İşlemi başlat
try {
    // Telegram widget'ından yetkilendirilmiş kullanıcı verilerini al
    $auth_data = checkTelegramAuthorization($_GET);

    // Kullanıcıyı yetkilendir
    userAuthentication($db, $auth_data);
} catch (Exception $e) {
    // Hataları göster
    die($e->getMessage());
}

// Kullanıcı sayfasına git
die(header('Location: user.php'));
?>
