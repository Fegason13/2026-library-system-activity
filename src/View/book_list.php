<?php
declare(strict_types=1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Books</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h1>Library Book List</h1>

    <table>
        <thead>
            <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Year</th>
                <th>Genre</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($books)) : ?>
                <?php foreach ($books as $book) : ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars((string) $book['book_id']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($book['title']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($book['author']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars((string) $book['year']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($book['genre']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">No books available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
