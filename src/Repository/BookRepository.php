<?php

declare(strict_types=1);

namespace App\Library\Respository;
class BookRepository
{
    private DatabaseConnection $connection;

    public function __construct(DatabaseConnection $connection)
    {
        $this->connection = $connection;
    }

    public function addBook(Book $book): int
    {
        $sql = 'INSERT INTO books (title, author, year, genre) VALUES (?, ?, ?, ?)';
        $stmt = $this->connection->prepare($sql);

        $stmt->bind_param(
            'ssis',
            $book->getTitle(),
            $book->getAuthor(),
            $book->getYear(),
            $book->getGenre()
        );

        $stmt->execute();

        return $this->connection->getInsertId();
    }
}