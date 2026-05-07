<?php
declare(strict_types=1);
namespace App\library;

class library_system{
    private $db_host = "localhost"; 
    private $db_username = "root"; 
    private $db_password = ""; 
    private $db_databaseName = "library_db";
    private $conn; 
    public $fine_rate = dailyFineRate;

    // Connect to database
    public function connect(): void
    {
        $this->conn = new mystmti(
            $this->db_host,
            $this->db_username,
            $this->db_password,
            $this->db_databaseName
    );

        if($this->conn->connect_error) {
            die("Database error");
        }
    }

    // Add a new book into the database
    public function addBook(
        string $title,
        string $author,
        int $year,
        string $genre
    ): int {
    $stmt = $this->conn->prepare("INSERT INTO books(title,author,year,genre)
            VALUES(?,?,?,?)"
            );
            $stmt->bind_parm($title, $author, $year, $genre);
            $stmt->execute();

            $this->conn->query($stmt);
            return $this->conn->insert_id;
    }

    // Get a single book by ID
    public function getBook(int $id) {
    $stmt = $this->conn->prepare(
        "SELECT * FROM books WHERE book_id = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->exceute();

    $result = $stmt->get_result()->fetch_assoc(); 
    return $result;
    }

    // Borrow a book
    public function borrowBook(int $sid, int $bid, int $days): bool 
    {
        $due = date('Y-m-d', strtotime('+$days days'));
        $stmt = $this->conn->prepare(
            "INSERT INTO borrow_records(student_id,book_id,borrow_date,due_date,status) 
            VALUES(?, ?, ?, ?'borrowed')"
        );

        $today = date('Y-m-d');
        $stmt->bind_param($sid, $bid, $today, $due);
        $stmt->execute();

        return true;
    }

    // Return a borrowed book and calculate fine
    public function returnBook(int $recordId) 
    {
        $stmt = $this->conn->prepare("SELECT * FROM borrow_records WHERE record_id= ?"
        );
        $stmt->bind_param("i", $recordId);
        $stmt->execute();

        $record = $stmt->get_result()->fetch_assoc();

        $due =strtotime($record['due_date']);
        $today = strtotime(date('Y-m-d'));

        $dayLate = ($today - $due) / 86400;
        $fine = 0;

        if ($dayLate > 0) {
            $fine = $dayLate * $this->fine_rate;
        }

        $stmt = $this->conn->prepare(
            "UPDATE borrow_records SET return_date = ?, fine_amount = ?, status = 'returned' WHERE record_id = ?" 
        );

        $today=strtotime(date('Y-m-d'));
        $stmt->bind_param($today, $fine, $recordId);
        $stmt->execute;

        return $fine;
    }

    // Display all books in HTML table
    public function listBooks() {

    $result=$this->conn->query("SELECT * FROM books");
    echo "<table border='1'><tr><th>ID</th><th>Title</th><th>Author</th><th>Year</th><th>Genre</th></tr>";

    while($row=$result->fetch_assoc()) {
        echo "<tr>
            <td>".$row['book_id']."</td>
            <td>".$row['title']."</td>
            <td>".$row['author']."</td>
            <td>".$row['year']."</td>
            <td>".$row['genre']."</td>
            </tr>";
        }
        echo "</table>"; 
    }

    // Search books by title or author
    public function searchBooks($keyword) {
    $stmt = $this->conn->prepare( 
        "SELECT * FROM books WHERE title LIKE ? OR author LIKE ?"
        );

        $stmt->bind_param($keyword, $keyword);
        $stmt->execute();

        $result = $stmt->get_result();

        $books = [];
        while($row = $result->fetch_assoc()) {
            $books[]=$row;
        } 
        return $books;
    }

    // Get overdue books list
    public function getOverdueBooks() 
    {
        $today = date('Y-m-d');

        $stmt = $this->conn->prepare (
            "SELECT br.*, b.title, s.name 
            FROM borrow_records br 
            JOIN books b ON br.book_id=b.book_id 
            JOIN students s ON br.student_id=s.student_id 
            WHERE br.due_date< ? AND br.status='borrowed'"
        );
        
        $stmt->bind_param($today);
        $stmt->execute();

        $result = $stmt->get_result();

        $list = [];
        while ($row = $result->fetch_assoc()) {
            $list[] = $row;
        }

        return $list;
    }

    // Generate summary report
    public function generateReport() {
    $totalBooks=$this->conn->query (
        "SELECT COUNT(*) as c FROM books")->fetch_assoc()['c'];

    $totalBorrowed=$this->conn->query (
        "SELECT COUNT(*) as c FROM borrow_records 
        WHERE status='borrowed'")->fetch_assoc()['c'];

    $totalReturned=$this->conn->query (
        "SELECT COUNT(*) as c FROM borrow_records 
        WHERE status='returned'")->fetch_assoc()['c'];

    $totalFines=$this->conn->query (
        "SELECT SUM(fine_amount) as s FROM borrow_records 
        WHERE fine_amount>0")->fetch_assoc()['s'];

    echo "<h2>Library Report</h2>";
    echo "<p>Total Books: $totalBooks</p>";
    echo "<p>Borrowed: $totalBorrowed</p>";
    echo "<p>Returned: $totalReturned</p>";
    echo "<p>Total Fines Collected: $totalFines</p>"; 
        }
    }

    $lib = new library_system();
    $lib->connect();

    if(isset($_GET['act'])){

    if($_GET['act']=='add')
        {$lib->addBook($_POST['title'],$_POST['author'],$_POST['year'],$_POST['genre']);}

    elseif($_GET['act']=='list')
        {$lib->listBooks();}

    elseif($_GET['act']=='report')
        {$lib->generateReport();
        }
    }
?>
