<?php declare(strict_types=1); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrow Book</title>
</head>
<body>
    <h1>Borrow a Book</h1>
    
    <form method="post" action="index.php?act=borrow">
        <div class="form-group">
            <label for="student_id">Student ID:</label>
            <input type="number" id="student_id" name="student_id" required>
        </div>
        
        <div class="form-group">
            <label for="book_id">Book ID:</label>
            <input type="number" id="book_id" name="book_id" required>
        </div>
        
        <div class="form-group">
            <label for="days">Borrow Period (days):</label>
            <input type="number" id="days" name="days" value="14" min="1" max="28" required>
        </div>
        
        <button type="submit">Borrow Book</button>
    </form>
</body>
</html>