<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reserve_isbn']) && isset($_SESSION['username'])) {
    $isbn = $conn->real_escape_string($_POST['reserve_isbn']);
    $username = $conn->real_escape_string($_SESSION['username']);
    $currentDate = date("Y-m-d");

    // Check if the book is already reserved
    $checkBookQuery = "SELECT reserved FROM books WHERE ISBN = '$isbn'";
    $checkBookResult = $conn->query($checkBookQuery);

    if ($checkBookResult->num_rows > 0) {
        $row = $checkBookResult->fetch_assoc();
        if ($row['reserved'] === 'Y') {
            $_SESSION['error'] = "This book is already reserved.";
        } else {
            // Mark the book as reserved in the books table
            $updateBookQuery = "UPDATE books SET reserved = 'Y' WHERE ISBN = '$isbn'";
            $reserveBookResult = $conn->query($updateBookQuery);

            // Insert reservation into the reservations table
            $insertReservationQuery = "INSERT INTO reservations (ISBN, username, reserveddate) 
                                        VALUES ('$isbn', '$username', '$currentDate')";
            $reserveInsertResult = $conn->query($insertReservationQuery);

            if ($reserveBookResult && $reserveInsertResult) {
                $_SESSION['success'] = "Book reserved successfully!";
            } else {
                $_SESSION['error'] = "Failed to reserve the book.";
            }
        }
    } else {
        $_SESSION['error'] = "Book not found.";
    }

    // Redirect to the page that shows the user's reservations
    header("Location: listOfReservations.php");
    exit;
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: search.php");
    exit;
}

$conn->close();
?>
