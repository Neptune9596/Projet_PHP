<?php

class Database
{
    private static $pdo = null;

    public static function getConnection()
    {
        if (self::$pdo === null) {
            try {
                // --- 1️⃣ Détection du mode (Priorité à Railway) ---
                $mysqlUrl = getenv('MYSQL_URL');

                if ($mysqlUrl) {
                    // 🔴 MODE PRODUCTION (Railway)
                    $url = parse_url($mysqlUrl);
                    $host = $url['host'];
                    $port = $url['port'] ?? 3306;
                    $db   = ltrim($url['path'], '/');
                    $user = $url['user'];
                    $pass = $url['pass'];
                } else {
                    // 🔵 MODE LOCAL (XAMPP / WAMP)
                    $host = "localhost";
                    $db   = "football"; 
                    $user = "root";
                    $pass = "";
                    $port = 3306;
                }

                // --- 2️⃣ Connexion ---
                $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

                self::$pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false, // Meilleure sécurité pour les injections SQL
                ]);

            } catch (PDOException $e) {
                // En cas d'erreur, on affiche un message propre au lieu d'une erreur 500 brute
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}