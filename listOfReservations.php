<?php
session_start();
include 'connection.php';

//if users not logged in
if (!isset($_SESSION['username'])) 
{
    // error message, redirect to login page
    $_SESSION['error'] = "Please log in to view your reservations";
    header("Location: login.php");
    exit;
}

$username = $conn->real_escape_string($_SESSION['username']);

// query to get the users reservations from the database, join reservations table and books table
$reservationsQuery = "SELECT books.ISBN, books.bookTitle, books.author, books.category, reservations.reserveddate 
                      FROM reservations 
                      JOIN books ON reservations.ISBN = books.ISBN 
                      WHERE reservations.username = '$username'";
$reservationsResult = $conn->query($reservationsQuery);
?>

<!--html for page-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <link rel="stylesheet"
     href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-
    ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
    crossorigin="anonymous">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <nav class="navbar bg-body-tertiary">
        <a href="index.php">Home</a>
        <a href="listOfReservations.php">My Reservations</a>
        <a href="search.php">Search</a>
        <a href="logout.php">Logout</a>
    </nav>
    <header> <h2>My Reservations</h2> </header>

    <?php
    
    //if the user has reservations, display them in a table (same structure as search page table)
    if ($reservationsResult->num_rows > 0) 
    {
        echo "<table border='1' cellpadding='10' cellspacing='0'>";
        echo "<tr><th>ISBN</th><th>Title</th><th>Author</th><th>Category</th><th>Reserved Date</th><th>Action</th></tr>";

        while ($row = $reservationsResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['ISBN']) . "</td>";
            echo "<td>" . htmlspecialchars($row['bookTitle']) . "</td>";
            echo "<td>" . htmlspecialchars($row['author']) . "</td>";
            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
            echo "<td>" . htmlspecialchars($row['reserveddate']) . "</td>";

            //delete button for deleting reservation
            echo "<td>
                    <form method='POST' action='deleteReservation.php'>
                        <input type='hidden' name='delete_isbn' value='" . htmlspecialchars($row['ISBN']) . "'>
                        <button type='submit'>Delete</button>
                    </form>
                  </td>";
            echo "</tr>";
        }

        echo "</table>";
    } 
    else //if user has no reservations
    {
        echo "<p>You have no reservations.</p>";
    }

    $conn->close();
    ?>
</body>
</html>
