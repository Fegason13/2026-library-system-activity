<?php

declare(strict_types=1);

namespace App\Repository;

use App\DatabaseConnection;
use App\Entity\Book;
use App\Exception\DatabaseException;
use App\Exception\ValidationException;

/**
 * Manages database operations for Book entities.
 *
 * This repository class encapsulates all SQL queries related to books,
 * ensuring consistent data access and preventing SQL injection through
 * prepared statements. It provides methods for CRUD operations
 * and search functionality.
 *
 * @author Haneen
 * @since 2026-05-06
 */
class BookRepository
{
    /**
     * @var DatabaseConnection The active database connection instance
     */
    private DatabaseConnection $connection;

    /**
     * Constructs a new BookRepository instance.
     *
     * @param DatabaseConnection $connection The database connection to use
     */
    public function __construct(DatabaseConnection $connection)
    {
        $this->connection = $connection;
    }


    public function addBook(Book $book): int
    {
        $this->validateBook($book);

        $sql = 'INSERT INTO books (title, author, year, genre) VALUES (?, ?, ?, ?)';
        $statement = $this->connection->prepare($sql);
        
        $statement->bind_param(
            'ssis',
            $book->getTitle(),
            $book->getAuthor(),
            $book->getYear(),
            $book->getGenre()
        );

        if (!$statement->execute()) {
            throw DatabaseException::queryFailed($statement->error);
        }

        return $this->connection->insertId();
    }


    public function findById(int $bookId): ?array
    {
        if ($bookId <= 0) {
            throw ValidationException::invalidBookId($bookId);
        }

        $sql = 'SELECT * FROM books WHERE book_id = ?';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param('i', $bookId);
        
        if (!$statement->execute()) {
            throw DatabaseException::queryFailed($statement->error);
        }

        $result = $statement->get_result();
        return $result->fetch_assoc();
    }

    public function searchBooks(string $keyword): array
    {
        $sql = 'SELECT * FROM books WHERE title LIKE ? OR author LIKE ?';
        $statement = $this->connection->prepare($sql);
        
        $searchPattern = '%' . $keyword . '%';
        $statement->bind_param('ss', $searchPattern, $searchPattern);
        
        if (!$statement->execute()) {
            throw DatabaseException::queryFailed($statement->error);
        }

        $result = $statement->get_result();
        $books = [];
        
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
        
        return $books;
    }

    public function getAllBooks(): array
    {
        $sql = 'SELECT * FROM books';
        $result = $this->connection->query($sql);
        
        $books = [];
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
        
        return $books;
    }


    public function getTotalBooksCount(): int
    {
        $sql = 'SELECT COUNT(*) as count FROM books';
        $result = $this->connection->query($sql);
        $row = $result->fetch_assoc();
        return (int) $row['count'];
    }


    private function validateBook(Book $book): void
    {
        if (empty(trim($book->getTitle()))) {
            throw ValidationException::emptyTitle();
        }
        
        if (empty(trim($book->getAuthor()))) {
            throw ValidationException::emptyAuthor();
        }
        
        if ($book->getYear() < 1000 || $book->getYear() > (int) date('Y')) {
            throw ValidationException::invalidYear($book->getYear());
        }
    }
}