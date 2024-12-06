<?php
session_start();
include 'connection.php';

//if user requests to reserve book
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reserve_isbn']) && isset($_SESSION['username'])) 
{
    $isbn = $conn->real_escape_string($_POST['reserve_isbn']);
    $username = $conn->real_escape_string($_SESSION['username']);
    $currentDate = date("Y-m-d");

    // query to check if book is already reserved
    $checkBookQuery = "SELECT reserved FROM books WHERE ISBN = '$isbn'";
    $checkBookResult = $conn->query($checkBookQuery);

    //if book is already reserved, display an error message
    if ($checkBookResult->num_rows > 0)
    {
        $row = $checkBookResult->fetch_assoc();

        if ($row['reserved'] === 'Y') 
        {
            $_SESSION['error'] = "This book is already reserved.";
        } 
        else 
        {
            // sql query to mark book as reserved in books table
            $updateBook = "UPDATE books SET reserved = 'Y' WHERE ISBN = '$isbn'";
            $reserveBookResult = $conn->query($updateBook);

            // sql query to insert reservation into the reservations table
            $insertReservation = "INSERT INTO reservations (ISBN, username, reserveddate) 
                                        VALUES ('$isbn', '$username', '$currentDate')";
            $reserveInsertResult = $conn->query($insertReservation);

            //if the query succeeds display a success message
            if ($reserveBookResult && $reserveInsertResult) 
            {
                $_SESSION['success'] = "Book reserved successfully!";

            } 
            else 
            {
                //error message
                $_SESSION['error'] = "Failed to reserve the book.";
            }
        }
    } 
    else 
    {
        //error message
        $_SESSION['error'] = "Book not found.";
    }

    // redirect the list of the user's reservations
    header("Location: listOfReservations.php");
    exit;
} 
else 
{
    //error message, reloads page
    $_SESSION['error'] = "Invalid request.";
    header("Location: search.php");
    exit;
}

$conn->close();
?>
