<?php

declare(strict_types=1);
namespace App\Library;

use DateInterval;
use DateTime;

class LibraryService
{
    public function calculateDueDate(DateTime $borrowDate): DateTime
    {
        $dueDate = clone $borrowDate;
        return $dueDate->add(new DateInterval('P14D'));
    }

    public function calculateFine(DateTime $dueDate, DateTime $returnDate): float
    {
        if ($returnDate <= $dueDate) {
            return 0.0;
        }

        $daysLate = $dueDate->diff($returnDate)->days;
        return $daysLate * 1.5;
    }
}