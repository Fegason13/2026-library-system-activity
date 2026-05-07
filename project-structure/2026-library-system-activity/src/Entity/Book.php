<?php
declare(strict_types=1);

namespace App\Entity;

class Book {
    /**
 * Manages the Book details
 * Entity class representing a book
 * This Entity use encapsulation for the books get and set.
 *
 * @author Haneen
 * @since 2026-05-06
 */

    private int $id;
    private string $title;
    private string $author;
    private int $year;
    private string $genre;

        public function __construct(
        string $title,
        string $author,
        int $year,
        string $genre,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->genre = $genre;
    }
    /**
     * Gets all id from Book.
     *
     * @return_array An array of all id
     */
    public function getId(): ?int{ 
        return $this->id;
    }

    public function getTitle(): string{
        return $this->title;
    }
    
    public function getAuthor(): string{
        return $this->author;
    }

    public function getYear(): int{
        return $this->year;
    }

    public function getGenre() : string{
        return $this->genre;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function setTitle(string $title): void{
        $this->title = $title;
    }

    public function setAuthor(string $author): void{
        $this->author = $author; 
    }

    public function setYear(int $year): void{
        $this->year = $year;
    }

    public function setGenre(string $genre): void{
        $this->genre = $genre;
    }
}
?>