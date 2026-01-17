<?php

class Database
{
    private static $pdo = null;

    public static function getConnection()
    {
        if (self::$pdo === null) {

            // --- 1️⃣ Détection du mode local ---
            $runningLocal = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);

            if ($runningLocal) {
                // 🔵 MODE LOCAL (XAMPP / WAMP)
                $host = "localhost";
                $db   = "football";     // <-- mets le nom de ta BDD locale
                $user = "root";
                $pass = "";
                $port = 3306;

            } else {
                // 🔴 MODE PRODUCTION (Railway)
                $url = parse_url(getenv('MYSQL_URL'));
                $host = $url['host'];
                $port = $url['port'] ?? 3306;
                $db   = ltrim($url['path'], '/');
                $user = $url['user'];
                $pass = $url['pass'];

                if (!$host || !$db || !$user || !$pass) {
                    die("❌ Railway : variables d'environnement manquantes.");
                }
            }

            // --- Connexion ---
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }

        return self::$pdo;
    }
}
