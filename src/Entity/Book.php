<?php

declare(strict_types=1);
namespace App\Library;

/**
 * Class Book
 * Represents a single book in the library system
 * Stores book details like title, author, year, and genre
 */
class Book
{
    // Book ID (can be null if not yet saved in database)
    private ?int $id;

    // Book title
    private string $title;

    // Author of the book
    private string $author;

    // Year the book was published
    private int $year;

    // Genre/category of the book
    private string $genre;

    /**
     * Constructor
     * Initializes a Book object with given values
     */
    public function __construct(
        int $id,
        string $title,
        string $author,
        int $year,
        string $genre
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->genre = $genre;
    }

    //Get book ID
    public function getId(): int { return $this->id; }

    //Get book title
    public function getTitle(): string { return $this->title; }

    //Get book author
    public function getAuthor(): string { return $this->author; }

    // Get publication year
    public function getYear(): int { return $this->year; }

    //Get book genre
    public function getGenre(): string { return $this->genre; }
}