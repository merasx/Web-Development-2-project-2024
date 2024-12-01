<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search for Books</title>
    <style>
        .reservation-form {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
            <nav class="navbar bg-body-tertiary">  
            <a href="index.php">Home</a>
            <a href= "listOfBooks.php">list of books</a>
            <a href= "search.php">search</a>
            <a href = "logout.php">logout</a>
            </nav>
    <h2>Search for a Book</h2>
    <?php
    if ( isset($_SESSION["error"]) ) 
    {
    echo('<p style="color:red">Error:'.$_SESSION["error"]."</p>\n");
    unset($_SESSION["error"]);
    }
    if ( isset($_SESSION["success"]) ) 
    {
    echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
    unset($_SESSION["success"]);
    }
    
    if ( ! isset($_SESSION["username"]) ) 
    { ?>
    Please <a href="login.php">Log In</a> to start.
    <?php }
    else { ?>
    <form action="" method="GET">
        <label for="title">Book Title:</label>
        <input type="text" id="title" name="title" placeholder="Enter book title"><br><br>

        <label for="author">Author:</label>
        <input type="text" id="author" name="author" placeholder="Enter author name"><br><br>

        <label for="category">Category:</label>
        <select id="category" name="category">
            <option value="">-- Select Category --</option>
            <?php

            include 'connection.php';

            // Retrieve categories dynamically
            $categoryQuery = "SELECT DISTINCT category FROM books";
            $categoryResult = $conn->query($categoryQuery);

            if ($categoryResult->num_rows > 0) {
                while ($categoryRow = $categoryResult->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($categoryRow['category']) . "'>" . htmlspecialchars($categoryRow['category']) . "</option>";
                }
            }
            ?>
        </select><br><br>

        <input type="submit" value="Search">
    </form>

    <?php
    // Handle reservation request
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reserve_isbn'])) {
        $isbnToReserve = $conn->real_escape_string($_POST['reserve_isbn']);
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);
        $reserveDate = $conn->real_escape_string($_POST['reserve_date']);

        // Check if the book is already reserved
        $checkReservationQuery = "SELECT reserved FROM books WHERE ISBN = '$isbnToReserve'";
        $checkReservationResult = $conn->query($checkReservationQuery);

        if ($checkReservationResult->num_rows > 0) {
            $row = $checkReservationResult->fetch_assoc();
            if ($row['reserved'] === 'Y') {
                echo "<p style='color: red;'>This book is already reserved.</p>";
            } else {
                // Insert reservation into the reservations table
                $insertReservationQuery = "INSERT INTO reservations (ISBN, username, password, reserve_date) 
                                            VALUES ('$isbnToReserve', '$username', '$password', '$reserveDate')";
                if ($conn->query($insertReservationQuery) === TRUE) {
                    // Update book reservation status
                    $reserveQuery = "UPDATE books SET reserved = 'Y' WHERE ISBN = '$isbnToReserve'";
                    $conn->query($reserveQuery);
                    echo "<p style='color: green;'>Book successfully reserved!</p>";
                } else {
                    echo "<p style='color: red;'>Failed to reserve the book: " . $conn->error . "</p>";
                }
            }
        }
    }

    // Handle search functionality
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $title = isset($_GET['title']) ? $conn->real_escape_string($_GET['title']) : '';
        $author = isset($_GET['author']) ? $conn->real_escape_string($_GET['author']) : '';
        $category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

        // Build the search query
        $sql = "SELECT * FROM books WHERE 1=1";

        if (!empty($title)) {
            $sql .= " AND bookTitle LIKE '%$title%'";
        }

        if (!empty($author)) {
            $sql .= " AND author LIKE '%$author%'";
        }

        if (!empty($category)) {
            $sql .= " AND category = '$category'";
        }

        $result = $conn->query($sql);

        // Display the search results
        if ($result->num_rows > 0) {
            echo "<h3>Search Results:</h3>";
            echo "<table border='1' cellpadding='10' cellspacing='0'>";
            echo "<tr><th>ISBN</th><th>Title</th><th>Author</th><th>Edition</th><th>Year</th><th>Category</th><th>Reserved</th><th>Reserve</th></tr>";

            while ($row = $result->fetch_assoc()) {
                $isbn = htmlspecialchars($row['ISBN']);
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['ISBN']) . "</td>";
                echo "<td>" . htmlspecialchars($row['bookTitle']) . "</td>";
                echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                echo "<td>" . htmlspecialchars($row['edition']) . "</td>";
                echo "<td>" . htmlspecialchars($row['year']) . "</td>";
                echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                echo "<td>" . htmlspecialchars($row['reserved']) . "</td>";
                echo "<td>";
                if ($row['reserved'] === 'Y') {
                    echo "<span style='color: red;'>Reserved</span>";
                } else {
                    echo "<button onclick='openReservationForm(\"$isbn\")'>Reserve</button>";
                    echo "<div id='reservation-form-$isbn' class='reservation-form'>
                            <form method='POST' action=''>
                                <input type='hidden' name='reserve_isbn' value='$isbn'>
                                Username: <input type='text' name='username' required><br><br>
                                Password: <input type='password' name='password' required><br><br>
                                Reservation Date: <input type='date' name='reserve_date' required><br><br>
                                <button type='submit'>Reserve</button>
                                <button type='button' onclick='closeReservationForm(\"$isbn\")'>Cancel</button>
                            </form>
                          </div>";
                }
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No results found for your search.</p>";
        }
    }

    // Close the database connection
    $conn->close();
    ?>

    <script>
        function openReservationForm(isbn) {
            // Show the reservation form for the selected book
            document.getElementById("reservation-form-" + isbn).style.display = "block";
        }

        function closeReservationForm(isbn) {
            // Hide the reservation form for the selected book
            document.getElementById("reservation-form-" + isbn).style.display = "none";
        }
    </script>
    <?php } ?>
</body>
</html>
