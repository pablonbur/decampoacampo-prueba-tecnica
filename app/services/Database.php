<?php

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    /**
     * @throws Exception
     */
    private function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s;',
            getenv('HOST'),
            getenv('DBNAME'),
            getenv('CHARSET')
        );

        $user = getenv('USERNAME');
        $pass = getenv('PASSWORD');

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }

//    private function __clone() {} // Prevengo que clonen la instancia
//    private function __wakeup() {} // Prevengo que hagan un serialize de la instancia
}