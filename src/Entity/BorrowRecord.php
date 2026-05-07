<?php

declare(strict_types=1);
namespace App\Library;

use DateTime;

class BorrowRecord
{
    private ?int $id;
    private int $bookId;
    private string $borrower;
    private DateTime $borrowDate;
    private ?DateTime $returnDate;

    public function __construct(
        int $id,
        int $bookId,
        string $borrower,
        DateTime $borrowDate,
        ?DateTime $returnDate
    ) {
        $this->id = $id;
        $this->bookId = $bookId;
        $this->borrower = $borrower;
        $this->borrowDate = $borrowDate;
        $this->returnDate = $returnDate;
    }

    public function getId(): ?int { return $this->id; }
    public function getBookId(): int { return $this->bookId; }
    public function getBorrower(): string { return $this->borrower; }
    public function getBorrowDate(): DateTime { return $this->borrowDate; }
    public function getReturnDate(): ?DateTime { return $this->returnDate; }
}