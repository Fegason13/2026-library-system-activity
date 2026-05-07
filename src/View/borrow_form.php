<?php
declare(strict_types=1);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form {
            width: 400px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }

        button {
            margin-top: 15px;
            padding: 10px 15px;
        }
    </style>
</head>
<body>

    <h1>Borrow a Book</h1>

    <form action="" method="POST">

        <label for="student_id">Student ID</label>
        <input
            type="number"
            id="student_id"
            name="student_id"
            required
        >

        <label for="book_id">Book ID</label>
        <input
            type="number"
            id="book_id"
            name="book_id"
            required
        >

        <label for="days">Number of Days</label>
        <input
            type="number"
            id="days"
            name="days"
            value="14"
            required
        >

        <button type="submit">
            Borrow Book
        </button>

    </form>

</body>
</html>