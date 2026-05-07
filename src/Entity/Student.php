<?php
declare(strict_types=1);
namespace App\library\Entity;


/**
 * Represents a student entity in the library system.
 * Stores and manages student information such as
 * student ID, full name, course, and year level.
 */
calss Student {
    private int $studentId;
    private int #yearLevel;


    public function __construct (
        int $studentId,
        string $name,
        string $course,
        int $yearLevel
    ) {
        $this->studentId = $studentId;
        $this->name = $name;
        $this->course = $course;
        $this->yearLevel = $yearLevel;
    }

    public function getStudentId(): int 
    {
        return $this->yearLevel;
    }

    public function setName(string $name): void 
    {
        $this->name = $name;
    }

    public function setCourse(string $course): void
    {
        $this->course = $course;
    }

    public function setYearLevel(int $yearLevel): void
    {
        $this->yearLevel = $yearLevel;
    }
}
?>