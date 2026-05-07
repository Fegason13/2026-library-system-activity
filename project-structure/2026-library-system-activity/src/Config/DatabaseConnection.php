<?php

declare(strict_types=1);

namespace App;

use App\Config\DatabaseConfig;
use App\Exception\DatabaseException;
use mysqli;

    /**
 * Handles only database connectivity
 *
 * @author Haneen
 * @since 2026-05-06
 */
class DatabaseConnection
{
    /**
     * @var mysqli The active database connection instance
     */
    private mysqli $connection;


    public function __construct(){
        $this->connect();
    }


    private function connect(): void{
        $this->connection = new mysqli(
            DatabaseConfig::host,
            DatabaseConfig::username,
            DatabaseConfig::password,
            DatabaseConfig::database
        );

        if ($this->connection->connect_error) {
            throw DatabaseException::connectionFailed($this->connection->connect_error);
        }
    }

    public function getConnection(): mysqli{
        return $this->connection;
    }

    public function prepare(string $sql): mysqli_sqli {
        $statement = $this->connection->prepare($sql);
        if (!$statement) {
            throw DatabaseException::queryFailed($this->connection->error);
        }
        return $statement;
    }


    public function query(string $sql): mysqli_result|bool{
        $result = $this->connection->query($sql);
        if (!$result) {
            throw DatabaseException::queryFailed($this->connection->error);
        }
        return $result;
    }

    public function insertId(): int{
        return $this->connection->insert_id;
    }

    /**
     * Closes the database connection.
     *
     * @return void
     */
    public function close(): void
    {
        $this->connection->close();
    }
}
