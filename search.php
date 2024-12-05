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
            <a href="listOfReservations.php">My Reservations</a>
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
                    // Updated reservation form
                    echo "<form method='POST' action='reservations.php'>
                            <input type='hidden' name='reserve_isbn' value='$isbn'>
                            <button type='submit'>Reserve</button>
                          </form>";
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
    <?php } ?>
</body>
</html>
