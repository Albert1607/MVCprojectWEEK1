<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    /**
     * Get or initialize the PDO database connection
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            // Driver selection: 'sqlite' or 'mysql'
            $driver = 'sqlite';

            try {
                if ($driver === 'sqlite') {
                    $dbDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'database';
                    if (!is_dir($dbDir)) {
                        mkdir($dbDir, 0777, true);
                    }
                    $dbPath = $dbDir . DIRECTORY_SEPARATOR . 'clinic.sqlite';
                    self::$instance = new PDO("sqlite:" . $dbPath);
                    self::$instance->exec("PRAGMA foreign_keys = ON;");
                } else {
                    // For MySQL (e.g. standard XAMPP MySQL setup)
                    $host = '127.0.0.1';
                    $dbname = 'clinic_mvc';
                    $user = 'root';
                    $pass = '';
                    $charset = 'utf8mb4';

                    $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";
                    self::$instance = new PDO($dsn, $user, $pass);
                }

                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                self::$instance->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
