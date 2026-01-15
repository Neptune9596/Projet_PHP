<?php

class Database
{
    private static $pdo = null;

    public static function getConnection()
    {
        if (self::$pdo === null) {

            // Railway MySQL URL
            $url = parse_url(getenv('MYSQL_URL'));

            $host = $url['host'];
            $port = $url['port'] ?? 3306;
            $db   = ltrim($url['path'], '/');
            $user = $url['user'];
            $pass = $url['pass'];

            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }

        return self::$pdo;
    }
}
