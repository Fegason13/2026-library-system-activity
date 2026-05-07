<?php
declare(strict_types=1);

namespace App\Entity;

    /**
 * Manages the Borrow Book details
 *Entity class representing a borrow transaction
 * This Entity use encapsulation for the borrow book get and set.
 *
 * @author Haneen
 * @since 2026-05-06
 */

class BorrowRecord{
    private int $studentId;
    private int $bookId;
    private string $borrowDate;
    private string $dueDate;
    private string $status;
    private int $id;
    private string $returnDate;
    private float $fineAmount;

        public function __construct(
        int $studentId,
        int $bookId,
        string $borrowDate,
        string $dueDate,
        string $status = 'borrowed',
        ?int $id = null,
        ?string $returnDate = null,
        float $fineAmount = 0.0
    ) {
        $this->id = $id;
        $this->studentId = $studentId;
        $this->bookId = $bookId;
        $this->borrowDate = $borrowDate;
        $this->dueDate = $dueDate;
        $this->returnDate = $returnDate;
        $this->status = $status;
        $this->fineAmount = $fineAmount;
    }

    public function getStudentId(): int{ 
        return $this->studentId;
    }

    public function getBookId(): int{ 
        return $this->bookId;
    }

    public function getBorrowDate(): string{ 
        return $this->borrowDate;
    }
    public function getDueDate(): string{ 
        return $this->dueDate;
    }

    public function getReturnDate(): string{ 
        return $this->returnDate;
    }

    public function getStatus() : string {
        return $this->status;
    }

    public function getFineAmount(): float{
        return $this->fineAmount;
    }

    public function setStudentId(int $studentId): void{
        $this->studentId = $studentId;
    }

    public function setBookId(int $bookId): void{
        $this->bookId = $bookId;
    }

    public function setBorrowDate(string $borrowDate): void{
        $this->borrowDate = $borrowDate;
    }

    public function setDueDate(string $dueDate): void{
        $this->dueDate = $dueDate;
    }

    public function setReturnDate(string $returnDate): void{
        $this->returnDate = $returnDate;
    }
    
    public function setFineAmount(float $fineAmount): void{
        $this->fineAmount = $fineAmount;
        $fineAmount = 5.00;
    }
    

}
?>