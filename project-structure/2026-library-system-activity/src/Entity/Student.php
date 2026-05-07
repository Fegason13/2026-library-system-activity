<?php
declare(strict_types=1);

namespace App\Entity;
    /**
 * Manages the students details
 *
 * This Entity use encapsulation for the students get and set.
 *
 * @author Haneen
 * @since 2026-05-06
 */
class Student{
    private int $id;
    private string $name;
    private string $email;

     public function __construct(
        int $id,
        string $name,
        string $email
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getName(): string{
        return $this->name;
    }

    public function getEmail(): string{
        return $this->email;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function setName(string $name): void{
        $this->name = $name;
    }

    public function setEmail(string $email): void{
        $this->email = $email;
    }



}
?>