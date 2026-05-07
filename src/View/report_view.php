<?php

declare(strict_types=1);
namespace App\Library;

class LibraryReport
{
    public function generateBorrowSummary(array $records): array
    {
        $summary = [];

        foreach ($records as $record) {
            $summary[] = [
                'book_id' => $record->getBookId(),
                'borrower' => $record->getBorrower(),
                'borrow_date' => $record->getBorrowDate()->format('Y-m-d')
            ];
        }

        return $summary;
    }
}