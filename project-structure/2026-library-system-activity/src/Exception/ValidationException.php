<?php

declare(strict_types=1);

namespace App\Exception;

use InvalidArgumentException;

/**
 * Manages the validation details.
 *
 * In this Exception we will see the validation if invalid or not
 *
 * @author Haneen
 * @since 2026-05-06
 */
class ValidationException extends InvalidArgumentException
{

    public static function invalidYear(int $year): self
    {
        return new self('Invalid publication year: ' . $year);
    }

    public static function invalidBookId(int $bookId): self
    {
        return new self('Invalid book ID: ' . $bookId);
    }

    public static function invalidStudentId(int $studentId): self
    {
        return new self('Invalid student ID: ' . $studentId);
    }

    public static function emptyTitle(): self
    {
        return new self('Book title cannot be empty');
    }

    public static function emptyAuthor(): self
    {
        return new self('Book author cannot be empty');
    }
}