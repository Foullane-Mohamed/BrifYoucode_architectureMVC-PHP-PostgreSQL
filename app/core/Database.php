<?php

namespace App\Core;

require __DIR__ . "/../../vendor/autoload.php";

use mysqli;
use mysqli_sql_exception;
use Dotenv\Dotenv;

class Database {
    private $mysqli;
    private static $instance = null;

    private function __construct() {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $db_host = $_ENV["DB_HOST"];
        $db_name = $_ENV["DB_DATABASE"];
        $port = $_ENV["DB_PORT"];
        $username = $_ENV["DB_USERNAME"];
        $password = $_ENV["DB_PASSWORD"];

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->mysqli = new mysqli($db_host, $username, $password, $db_name, $port);
            $this->mysqli->set_charset('utf8mb4');
            // echo "success ";
        } catch (mysqli_sql_exception $e) {
            echo "failed to connect: " . $e->getMessage();
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance->mysqli;
    }

    private function __clone() {}

    public function __wakeup() {}
}

Database::getInstance();