<?php

declare(strict_types=1);
namespace App\Library\Respository;

class BorrowRepository
{
    private DatabaseConnection $connection;

    public function __construct(DatabaseConnection $connection)
    {
        $this->connection = $connection;
    }

    public function borrowBook(BorrowRecord $record): int
    {
        $sql = 'INSERT INTO borrows (book_id, borrower, borrow_date) VALUES (?, ?, ?)';
        $stmt = $this->connection->prepare($sql);

        $borrowDate = $record->getBorrowDate()->format('Y-m-d');

        $stmt->bind_param(
            'iss',
            $record->getBookId(),
            $record->getBorrower(),
            $borrowDate
        );

        $stmt->execute();

        return $this->connection->getInsertId();
    }

    public function returnBook(int $borrowId, string $returnDate): void
    {
        $sql = 'UPDATE borrows SET return_date = ? WHERE id = ?';
        $stmt = $this->connection->prepare($sql);

        $stmt->bind_param('si', $returnDate, $borrowId);
        $stmt->execute();
    }
}