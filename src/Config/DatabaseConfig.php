<?php

declare(strict_types=1);
namespace App\Library;

use mysqli;
use mysqli_stmt;

/**
 * Class DatabaseConnection
 * Handles database connection and basic query preparation
 */
class DatabaseConnection
{
    // Store the mysqli connection object
    private mysqli $connection;

    /**
     * Constructor
     * Creates a new database connection using given credentials
     */
    public function __construct(
        string $host,
        string $user,
        string $password,
        string $database
    ) {
        // Establish connection to MySQL database
        $this->connection = new mysqli($host, $user, $password, $database);
    }

    /**
     * Prepare an SQL statement
     * Used to prevent SQL injection and improve security
     */
    public function prepare(string $sql): mysqli_stmt
    {
        return $this->connection->prepare($sql);
    }

    /**
     * Get the ID of the last inserted record
     * Useful after INSERT queries
     */
    public function getInsertId(): int
    {
        return $this->connection->insert_id;
    }

    /**
     * Get the raw mysqli connection object
     * Useful if advanced database operations are needed
     */
    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}