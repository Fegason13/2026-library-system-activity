<?php

declare(strict_types=1);
namespace App\Library\Config;

/**
 * Class LibraryConfig
 * Stores constant values used in the library system
 */
class LibraryConfig
{
    // Fine rate per day for overdue books
    public const DAILY_FINE_RATE = 5.0;

    // Status when a book is currently borrowed
    public const STATUS_BORROWED = 'borrowed';

    // Status when a book has been returned
    public const STATUS_RETURNED = 'returned';
}