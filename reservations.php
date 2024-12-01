<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reserve_isbn']) && isset($_POST['username'])) {
    $isbn = $conn->real_escape_string($_POST['reserve_isbn']);
    $username = $conn->real_escape_string($_POST['username']);
    $currentDate = date("Y-m-d");

    // Check if the book is already reserved
    $checkBookQuery = "SELECT reserved FROM books WHERE ISBN = '$isbn'";
    $checkBookResult = $conn->query($checkBookQuery);

    if ($checkBookResult->num_rows > 0) {
        $row = $checkBookResult->fetch_assoc();
        if ($row['reserved'] === 'Y') {
            echo json_encode(["success" => false, "message" => "This book is already reserved."]);
        } else {
            // Update books table to mark as reserved
            $updateBookQuery = "UPDATE books SET reserved = 'Y' WHERE ISBN = '$isbn'";
            $reserveBookResult = $conn->query($updateBookQuery);

            // Insert into Reservations table
            $insertReservationQuery = "INSERT INTO reservations (ISBN, Username, ReservedDate) 
                                        VALUES ('$isbn', '$username', '$currentDate')";
            $reserveInsertResult = $conn->query($insertReservationQuery);

            if ($reserveBookResult && $reserveInsertResult) {
                echo json_encode(["success" => true, "message" => "Book reserved successfully!"]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to reserve the book."]);
            }
        }
    } else {
        echo json_encode(["success" => false, "message" => "Book not found."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

$conn->close();
?>
