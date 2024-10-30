<?php
declare(strict_types=1);

namespace App;

use PDO;
use PDOException;

class DB
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            // Hardcoded, but should be in a config file (.env)
            $db_host = 'localhost';
            $db_name = 'zadanie';
            $db_username = 'zadanie_user';
            $db_password = '123haslo456';
            $port = 3307;

            try {
                self::$connection = new PDO("mysql:host=$db_host;dbname=$db_name;port=$port", $db_username, $db_password);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}