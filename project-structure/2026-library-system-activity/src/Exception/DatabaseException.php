<?php
declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

/**
 * Manages the database error.
 *
 * In this Exception is for database checker if it has error.
 *
 * @author Haneen
 * @since 2026-05-06
 */
class DatabaseException extends RuntimeException{
     public static function connectionFailed(string $message): self
    {
        return new self('Database connection failed: ' . $message);
    }
     public static function queryFailed(string $message): self
    {
        return new self('Database query failed: ' . $message);
    }
}

?>