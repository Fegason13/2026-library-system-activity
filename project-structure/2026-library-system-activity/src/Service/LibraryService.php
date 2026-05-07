<?php

declare(strict_types=1);

namespace App\Service;

use App\Config\LibraryConfig;
use App\DatabaseConnection;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\BorrowRepository;

class LibraryService
{
    /**
     * @var BookRepository The repository for book operations
     */
    private BookRepository $bookRepository;
    
    /**
     * @var BorrowRepository The repository for borrow operations
     */
    private BorrowRepository $borrowRepository;

    public function __construct(DatabaseConnection $connection){
        $this->bookRepository = new BookRepository($connection);
        $this->borrowRepository = new BorrowRepository($connection);
    }

    public function addBook(string $title, string $author, int $year, string $genre): int{
        $book = new Book($title, $author, $year, $genre);
        return $this->bookRepository->addBook($book);
    }
    
    public function findBookById(int $bookId): ?array{
        return $this->bookRepository->findById($bookId);
    }

    public function searchBooks(string $keyword): array{
        return $this->bookRepository->searchBooks($keyword);
    }

    public function getAllBooks(): array{
        return $this->bookRepository->getAllBooks();
    }

    public function borrowBook(int $studentId, int $bookId, int $days = LibraryConfig::DEFAULT_BORROW_DAYS): int{
        return $this->borrowRepository->createBorrowRecord($studentId, $bookId, $days);
    }

    public function returnBook(int $recordId): float{
        return $this->borrowRepository->returnBook($recordId);
    }
    
    public function getOverdueBooks(): array{
        return $this->borrowRepository->getOverdueBooks();
    }

    public function generateReportData(): array{
        return [
            'totalBooks' => $this->bookRepository->getTotalBooksCount(),
            'totalBorrowed' => $this->borrowRepository->getBorrowedCount(),
            'totalReturned' => $this->borrowRepository->getReturnedCount(),
            'totalFines' => $this->borrowRepository->getTotalFines()
        ];
    }
}