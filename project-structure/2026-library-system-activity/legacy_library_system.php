<?php

declare(strict_types=1);

use RuntimeException;
use InvalidArgumentException;
/**
 * @author Jericho
 * @since 2026-05-06
 */
class LibrarySystem{

    public string $host = "localhost";
    public string $username = "root";
    public string $password = "";
    public string $name = "library_db";
    public mysqli $conn;
    public int $dailyFineRate = 5;

    public function connect()
    {
        $this->conn = new mysqli(
            $this->host,
            $this->username,
            $this->password, 
            $this->name);

       if ($this->conn->connect_error) {
            throw new RuntimeException(
            'Database connection failed: ' . $this->conn->connect_error
            );
        }

    }
    public function addBook(string $title, string $author, int $year, string $genre): int
    {
        $sql = "INSERT INTO books(title,author,year,genre) VALUES('" . $title . "','" . $author . "'," . $year . ",'" . $genre . "')";
        $this->conn->query($sql);
        return (int)$this->conn->insert_id;
    }
    public function findById(int $bookId): ?array
    {
        $sql = "SELECT * FROM books WHERE book_id=" . $bookId;
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
    public function borrowBook(int $student_id, int $book_id, int $days): bool
    {
        $due = date('Y-m-d', strtotime('+' . $days . ' days'));
        $sql = "INSERT INTO borrow_records(student_id,book_id,borrow_date,due_date,status) VALUES(" . $student_id . "," . $book_id . ",'" . date('Y-m-d') . "','" . $due . "','STATUS_BORROWED = 'borrowed')";
        $this->conn->query($sql);
        return true;
    }
     public function returnBook(int $recordId): float 
     
    {
        $sql = "SELECT * FROM borrow_records WHERE record_id=" . $recordId;
        $record = $this->conn->query($sql)->fetch_assoc();
        $due = strtotime($record['due_date']);
        $today = strtotime(date('Y-m-d'));
        $diff = ($today - $due) / (60 * 60 * 24);
        $fine = 0;
        if ($diff > 0) {
            $fine = $diff * $this->dailyFineRate;
        }
        $sql2 = "UPDATE borrow_records SET return_date='" . date('Y-m-d') . "', fine_amount=" . $fine . ", status='returned' WHERE record_id=" . $recordId;
        $this->conn->query($sql2);
        return $fine;
    }

    public function listBooks():void
    {
        $sql = "SELECT * FROM books";
        $result = $this->conn->query($sql);
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Title</th><th>Author</th><th>Year</th><th>Genre</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row['book_id'] . "</td><td>" . $row['title'] . "</td><td>" . $row['author'] . "</td><td>" . $row['year'] . "</td><td>" . $row['genre'] . "</td></tr>";
        }
        echo "</table>";
    }
    public function searchBooks(string $kw):array
    {
        $sql = "SELECT * FROM books WHERE title LIKE '%" . $kw . "%' OR author LIKE '%" . $kw . "%'";
        $result = $this->conn->query($sql);
        $books = [];
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
        return $books;
    }
    public function getOverdueBooks():array
    {
        $sql = "SELECT br.*, book.title, student.name FROM borrow_records br JOIN books b ON br.book_id=b.book_id JOIN students s ON br.student_id=s.student_id WHERE br.due_date<'" . date('Y-m-d') . "' AND br.status='borrowed'";
        $result = $this->conn->query($sql);
        $list = [];
        while ($row = $result->fetch_assoc()) {
            $list[] = $row;
        }
        return $list;
    }
    public function generateReport():void
    {
        $totalBooks = $this->conn->query("SELECT COUNT(*) as c FROM books")->fetch_assoc()['c'];
        $totalBorrowed = $this->conn->query("SELECT COUNT(*) as c FROM borrow_records WHERE status='borrowed'")->fetch_assoc()['c'];
        $totalReturned = $this->conn->query("SELECT COUNT(*) as c FROM borrow_records WHERE status='returned'")->fetch_assoc()['c'];
        $totalFines = $this->conn->query("SELECT SUM(fine_amount) as s FROM borrow_records WHERE fine_amount>0")->fetch_assoc()['s'];

        echo "<h2>Library Report</h2>";
        echo "<p>Total Books: " . $totalBooks . "</p>";
        echo "<p>Borrowed: " . $totalBorrowed . "</p>";
        echo "<p>Returned: " . $totalReturned . "</p>";
        echo "<p>Total Fines Collected: $" . $totalFines . "</p>";
    }
}
$lib = new LibrarySystem();
$lib->connect();
if (isset($_GET['act'])) {
    if ($_GET['act'] == 'add') {
        $lib->addBook($_POST['title'], $_POST['author'], $_POST['year'], $_POST['genre']);
    } elseif ($_GET['act'] == 'list') {
        $lib->listBooks();
    } elseif ($_GET['act'] == 'report') {
        $lib->generateReport();
    }
}
