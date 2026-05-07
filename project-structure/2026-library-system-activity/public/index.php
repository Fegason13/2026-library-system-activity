<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/Config/DatabaseConfig.php';
require_once __DIR__ . '/../src/Config/LibraryConfig.php';
require_once __DIR__ . '/../src/Exception/DatabaseException.php';
require_once __DIR__ . '/../src/Exception/ValidationException.php';
require_once __DIR__ . '/../src/Entity/Book.php';
require_once __DIR__ . '/../src/Entity/BorrowRecord.php';
require_once __DIR__ . '/../src/Entity/Student.php';
require_once __DIR__ . '/../src/Repository/BookRepository.php';
require_once __DIR__ . '/../src/Repository/BorrowRepository.php';
require_once __DIR__ . '/../src/Service/LibraryService.php';

use App\DatabaseConnection;
use App\Service\LibraryService;
use App\Exception\DatabaseException;
use App\Exception\ValidationException;

$connection = null;
$libraryService = null;

try {
    $connection = new DatabaseConfig();
    $libraryService = new LibraryService($connection);
} catch (DatabaseException $e) {
    die('Database error: ' . $e->getMessage());
}

$action = $_GET['act'] ?? '';

switch ($action) {
    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $bookId = $libraryService->addBook(
                    $_POST['title'] ?? '',
                    $_POST['author'] ?? '',
                    (int) ($_POST['year'] ?? 0),
                    $_POST['genre'] ?? ''
                );
                echo "Book added successfully with ID: $bookId";
            } catch (ValidationException | DatabaseException $e) {
                echo 'Error: ' . $e->getMessage();
            }
        }
        break;

    case 'list':
        try {
            $books = $libraryService->getAllBooks();
            include __DIR__ . '/../src/View/book_list.php';
        } catch (DatabaseException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        break;

    case 'search':
        $keyword = $_GET['q'] ?? '';
        try {
            $books = $libraryService->searchBooks($keyword);
            include __DIR__ . '/../src/View/book_list.php';
        } catch (DatabaseException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        break;

    case 'borrow':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $recordId = $libraryService->borrowBook(
                    (int) ($_POST['student_id'] ?? 0),
                    (int) ($_POST['book_id'] ?? 0),
                    (int) ($_POST['days'] ?? 14)
                );
                echo "Book borrowed successfully with record ID: $recordId";
            } catch (ValidationException | DatabaseException $e) {
                echo 'Error: ' . $e->getMessage();
            }
        } else {
            include __DIR__ . '/../src/View/borrow_form.php';
        }
        break;

    case 'return':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $fine = $libraryService->returnBook((int) ($_POST['record_id'] ?? 0));
                echo "Book returned successfully. Fine: $" . number_format($fine, 2);
            } catch (ValidationException | DatabaseException $e) {
                echo 'Error: ' . $e->getMessage();
            }
        }
        break;

    case 'overdue':
        try {
            $overdueBooks = $libraryService->getOverdueBooks();
            echo '<h1>Overdue Books</h1>';
            echo '<table border="1">';
            echo '<tr><th>Record ID</th><th>Book Title</th><th>Student Name</th><th>Due Date</th></tr>';
            foreach ($overdueBooks as $book) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($book['record_id']) . '</td>';
                echo '<td>' . htmlspecialchars($book['title']) . '</td>';
                echo '<td>' . htmlspecialchars($book['name']) . '</td>';
                echo '<td>' . htmlspecialchars($book['due_date']) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } catch (DatabaseException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        break;

    case 'report':
        try {
            $report = $libraryService->generateReportData();
            include __DIR__ . '/../src/View/report_view.php';
        } catch (DatabaseException $e) {
            echo 'Error: ' . $e->getMessage();
        }
        break;

    default:
        echo '<h1>Library Management System</h1>';
        echo '<ul>';
        echo '<li><a href="?act=list">List all books</a></li>';
        echo '<li><a href="?act=borrow">Borrow a book</a></li>';
        echo '<li><a href="?act=overdue">View overdue books</a></li>';
        echo '<li><a href="?act=report">Generate report</a></li>';
        echo '</ul>';
        break;
}
?>