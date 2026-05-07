<?php

declare(strict_types=1);

namespace App\Repository;

use App\Config\LibraryConfig;
use App\DatabaseConnection;
use App\Exception\DatabaseException;
use App\Exception\ValidationException;
/**
 * 
 * Handles all borrow/return database queries
 * In this repository class you can see all the borrow book,
 * and create book record
 *
 * @author Haneen
 * @since 2026-05-06
 */
class BorrowRepository{

    private DatabaseConnection $connection;

    public function __construct(DatabaseConnection $connection)
    {
        $this->connection= $connection;
    }

     public function createBorrowRecord(int $studentId, int $bookId, int $days): int
    {
        $this->validateBorrowData($studentId, $bookId, $days);

        $borrowDate = date('Y-m-d');
        $dueDate = date('Y-m-d', strtotime('+' . $days . ' days'));

        $sql = 'INSERT INTO borrow_records (student_id, book_id, borrow_date, due_date, status) VALUES (?, ?, ?, ?, ?)';
        $statement = $this->connection->prepare($sql);
        
        $status = LibraryConfig::STATUS_BORROWED;
        $statement->bind_param('iisss', $studentId, $bookId, $borrowDate, $dueDate, $status);

        if (!$statement->execute()) {
            throw DatabaseException::queryFailed($statement->error);
        }

        return $this->connection->insertId();
    }

    public function returnBook(int $recordId): float
    {
        if ($recordId <= 0) {
            throw ValidationException::invalidBookId($recordId);
        }

        // Get the record to calculate fine
        $record = $this->findById($recordId);
        if (!$record) {
            throw ValidationException::invalidBookId($recordId);
        }

        $fine = $this->calculateFine($record['due_date']);
        $returnDate = date('Y-m-d');

        $sql = 'UPDATE borrow_records SET return_date = ?, fine_amount = ?, status = ? WHERE record_id = ?';
        $statement = $this->connection->prepare($sql);
        
        $status = LibraryConfig::STATUS_RETURNED;
        $statement->bind_param('sdsi', $returnDate, $fine, $status, $recordId);

        if (!$statement->execute()) {
            throw DatabaseException::queryFailed($statement->error);
        }

        return $fine;
    }

     public function findById(int $recordId): ?array
    {
        $sql = 'SELECT * FROM borrow_records WHERE record_id = ?';
        $statement = $this->connection->prepare($sql);
        $statement->bind_param('i', $recordId);
        
        if (!$statement->execute()) {
            throw DatabaseException::queryFailed($statement->error);
        }

        $result = $statement->get_result();
        return $result->fetch_assoc();
    }

     public function getOverdueBooks(): array
    {
        $sql = 'SELECT br.*, b.title, s.name 
                FROM borrow_records br 
                JOIN books b ON br.book_id = b.book_id 
                JOIN students s ON br.student_id = s.student_id 
                WHERE br.due_date < ? AND br.status = ?';
        
        $statement = $this->connection->prepare($sql);
        
        $today = date('Y-m-d');
        $status = LibraryConfig::STATUS_BORROWED;
        $statement->bind_param('ss', $today, $status);
        
        if (!$statement->execute()) {
            throw DatabaseException::queryFailed($statement->error);
        }

        $result = $statement->get_result();
        $overdueBooks = [];
        
        while ($row = $result->fetch_assoc()) {
            $overdueBooks[] = $row;
        }
        
        return $overdueBooks;
    }

    public function getBorrowedCount(): int
    {
        $sql = 'SELECT COUNT(*) as count FROM borrow_records WHERE status = ?';
        $statement = $this->connection->prepare($sql);
        
        $status = LibraryConfig::STATUS_BORROWED;
        $statement->bind_param('s', $status);
        
        if (!$statement->execute()) {
            throw DatabaseException::queryFailed($statement->error);
        }

        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        return (int) $row['count'];
    }

    public function getReturnedCount(): int
    {
        $sql = 'SELECT COUNT(*) as count FROM borrow_records WHERE status = ?';
        $statement = $this->connection->prepare($sql);
        
        $status = LibraryConfig::STATUS_RETURNED;
        $statement->bind_param('s', $status);
        
        if (!$statement->execute()) {
            throw DatabaseException::queryFailed($statement->error);
        }

        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        return (int) $row['count'];
    }

     public function getTotalFines(): float
    {
        $sql = 'SELECT SUM(fine_amount) as total FROM borrow_records WHERE fine_amount > 0';
        $result = $this->connection->query($sql);
        $row = $result->fetch_assoc();
        return (float) ($row['total'] ?? 0.0);
    }

     private function calculateFine(string $dueDate): float
    {
        $due = strtotime($dueDate);
        $today = strtotime(date('Y-m-d'));
        $daysOverdue = ($today - $due) / (60 * 60 * 24);

        if ($daysOverdue > 0) {
            return $daysOverdue * LibraryConfig::DAILY_FINE_RATE;
        }

        return 0.0;
    }

     private function validateBorrowData(int $studentId, int $bookId, int $days): void
    {
        if ($studentId <= 0) {
            throw ValidationException::invalidStudentId($studentId);
        }
        
        if ($bookId <= 0) {
            throw ValidationException::invalidBookId($bookId);
        }
        
        if ($days <= 0 || $days > LibraryConfig::DEFAULT_BORROW_DAYS * 2) {
            throw new ValidationException('Invalid borrow period: ' . $days);
        }
    }
}
?>