<?php
session_start();
include 'connection.php';

//if user requests to delete reservation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_isbn']) && isset($_SESSION['username'])) 
{

    $isbn = $conn->real_escape_string($_POST['delete_isbn']);
    $username = $conn->real_escape_string($_SESSION['username']);

    // query to delete reservation from reservations table
    $deleteReservationQuery = "DELETE FROM reservations WHERE ISBN = '$isbn' AND username = '$username'";
    $deleteResult = $conn->query($deleteReservationQuery);

    // mark the book as not reserved in books table
    $updateBookQuery = "UPDATE books SET reserved = 'N' WHERE ISBN = '$isbn'";
    $updateBookResult = $conn->query($updateBookQuery);

    //if the book deletes successfully, display message
    if ($deleteResult && $updateBookResult) 
    {
        $_SESSION['success'] = "Reservation deleted successfully!";
    } 
    else //error
    {
        $_SESSION['error'] = "Failed to delete the reservation.";
    }

    // reload page with deleted reservation
    header("Location: listOfReservations.php");
    exit;
} 
else 
{
    //if theres an error
    $_SESSION['error'] = "Invalid request.";

    //reload page
    header("Location: listOfReservations.php");
    exit;
}

$conn->close();
?>
