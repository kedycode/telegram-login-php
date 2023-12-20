<?php
// Veritabanı Objesi
class Database
{
    private $connection = null;
    public function __construct($dbhost = "", $dbname = "", $username = "", $password = "")
    {
        try {
            $this->connection = new PDO("mysql:host={$dbhost};dbname={$dbname};charset=utf8mb4;", $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // İfadeyi Yürüt
    private function executeStatement($statement = "", $parameters = [])
    {
        try {
            $stmt = $this->connection->prepare($statement);
            $stmt->execute($parameters);
            return $stmt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Veritabanına Satır/Satırlar Ekleme - INSERT (Oluşturma)
    public function Insert($statement = "", $parameters = [])
    {
        try {
            $this->executeStatement($statement, $parameters);
            return $this->connection->lastInsertId();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Veritabanından Satır/Satırlar Seçme - SELECT (Okuma)
    public function Select($statement = "", $parameters = [])
    {
        try {
            $stmt = $this->executeStatement($statement, $parameters);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Veritabanında Satır/Satırları Güncelleme - UPDATE
    public function Update($statement = "", $parameters = [])
    {
        try {
            $this->executeStatement($statement, $parameters);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Veritabanından Satır/Satırları Silme - DELETE
    public function Remove($statement = "", $parameters = [])
    {
        try {
            $this->executeStatement($statement, $parameters);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}


// Veritabanına Bağlanma
$db = new Database(
    "127.0.0.1",      // Host
    "telegram_login", // Veritabanı
    "root",           // Kullanıcı Adı
    ""                // Şifre
);
