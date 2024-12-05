<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    $_SESSION['error'] = "Please log in to view your reservations.";
    header("Location: login.php");
    exit;
}

$username = $conn->real_escape_string($_SESSION['username']);

// Fetch the user's reservations
$reservationsQuery = "SELECT books.ISBN, books.bookTitle, books.author, books.category, reservations.reserveddate 
                      FROM reservations 
                      JOIN books ON reservations.ISBN = books.ISBN 
                      WHERE reservations.username = '$username'";
$reservationsResult = $conn->query($reservationsQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <nav class="navbar bg-body-tertiary">
        <a href="index.php">Home</a>
        <a href="listOfReservations.php">My Reservations</a>
        <a href="search.php">Search</a>
        <a href="logout.php">Logout</a>
    </nav>

    <h2>My Reservations</h2>
    <?php
    if (isset($_SESSION['success'])) {
        echo "<p style='color:green'>" . $_SESSION['success'] . "</p>";
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo "<p style='color:red'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }

    if ($reservationsResult->num_rows > 0) {
        echo "<table border='1' cellpadding='10' cellspacing='0'>";
        echo "<tr><th>ISBN</th><th>Title</th><th>Author</th><th>Category</th><th>Reserved Date</th></tr>";

        while ($row = $reservationsResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['ISBN']) . "</td>";
            echo "<td>" . htmlspecialchars($row['bookTitle']) . "</td>";
            echo "<td>" . htmlspecialchars($row['author']) . "</td>";
            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
            echo "<td>" . htmlspecialchars($row['reserveddate']) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>You have no reservations.</p>";
    }

    $conn->close();
    ?>
</body>
</html>
