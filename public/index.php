<?php

declare(strict_types=1);

require_once '../src/Config/DatabaseConnection.php';
require_once '../src/Config/LibraryConfig.php';
require_once '../src/Entity/Book.php';
require_once '../src/Repository/BookRepository.php';
require_once '../src/Repository/BorrowRepository.php';
require_once '../src/Service/LibraryService.php';
require_once '../src/Exception/DatabaseException.php';
require_once '../src/Exception/ValidationException.php';

use App\Config\DatabaseConnection;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\BorrowRepository;
use App\Service\LibraryService;

try {
    $databaseConnection = new DatabaseConnection(
        'localhost',
        'root',
        '',
        'library_db'
    );

    $connection = $databaseConnection->getConnection();

    $bookRepository = new BookRepository($connection);
    $borrowRepository = new BorrowRepository($connection);

    $libraryService = new LibraryService($borrowRepository);

    $action = $_GET['act'] ?? '';

    switch ($action) {

        case 'add':

            $book = new Book(
                $_POST['title'] ?? '',
                $_POST['author'] ?? '',
                (int) ($_POST['year'] ?? 0),
                $_POST['genre'] ?? ''
            );

            $bookId = $bookRepository->add($book);

            echo 'Book added successfully. Book ID: ' . $bookId;

            break;

        case 'list':

            $books = $connection
                ->query('SELECT * FROM books')
                ->fetch_all(MYSQLI_ASSOC);

            require '../src/View/book_list.php';

            break;

        case 'borrow':

            $libraryService->borrowBook(
                (int) ($_POST['student_id'] ?? 0),
                (int) ($_POST['book_id'] ?? 0),
                (int) ($_POST['days'] ?? 14)
            );

            echo 'Book borrowed successfully.';

            break;

        case 'borrow-form':

            require '../src/View/borrow_form.php';

            break;

        case 'return':

            $recordId = (int) ($_GET['record_id'] ?? 0);

            $fine = $libraryService->returnBook($recordId);

            echo 'Book returned successfully. Fine: ₱' . $fine;

            break;

        default:

            echo '<h1>Student Library Management System</h1>';

            echo '<ul>';
            echo '<li><a href="?act=list">View Books</a></li>';
            echo '<li><a href="?act=borrow-form">Borrow Book</a></li>';
            echo '</ul>';

            break;
    }
} catch (Exception $exception) {

    echo 'Error: ' . htmlspecialchars($exception->getMessage());
}