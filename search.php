<?php 
session_start();
include 'connection.php';

// defined items per page for pagination
$items_per_page = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search for Books</title>

    <!--link to bootstrap-->
    <link rel="stylesheet"
     href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-
    ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
    crossorigin="anonymous">
    
    <!--link to css file, echo time() allows it to constantly update as i update the file-->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <!--navigation bar-->
    <nav class="navbar bg-body-tertiary">  
        <a href="index.php">Home</a>
        <a href="listOfReservations.php">My Reservations</a>
        <a href="search.php">Search</a>
        <a href="logout.php">Logout</a>
    </nav>
    <header>
    <h2>Search for a Book</h2>
    </header>   
    <?php

    //if users not logged in
    if (!isset($_SESSION["username"])) { 
        ?>
        Please <a href="login.php">Log In</a> to start.
        <?php 
    } 
    else //show page
    { ?>
        <form action="" method="GET">
            <label for="title">Book Title:</label>
            <input type="text" id="title" name="title" placeholder="Enter book title" value="<?php echo htmlspecialchars($_GET['title'] ?? ''); ?>"><br><br>

            <label for="author">Author:</label>
            <input type="text" id="author" name="author" placeholder="Enter author name" value="<?php echo htmlspecialchars($_GET['author'] ?? ''); ?>"><br><br>

            <label for="category">Category:</label>
            <select id="category" name="category">
                <option value="">-- Select Category --</option>
                <?php
                // query to get categoryID and categoryDescription from categories
                $categoryQuery = "SELECT CategoryID, CategoryDescription FROM Categories";
                $categoryResult = $conn->query($categoryQuery);

                //if successful
                if ($categoryResult->num_rows > 0) 
                {
                    while ($categoryRow = $categoryResult->fetch_assoc()) {
                        $selected = (isset($_GET['category']) && $_GET['category'] == $categoryRow['CategoryID']) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($categoryRow['CategoryID']) . "' $selected>" . htmlspecialchars($categoryRow['CategoryDescription']) . "</option>";
                    }
                }
                ?>
            </select><br><br>

            <input type="submit" value="Search">
        </form>

        <?php
        $title = isset($_GET['title']) ? $conn->real_escape_string($_GET['title']) : '';
        $author = isset($_GET['author']) ? $conn->real_escape_string($_GET['author']) : '';
        $category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

        // join books table and categories table by category id
        $sql = "SELECT books.*, categories.CategoryDescription 
                FROM books 
                LEFT JOIN categories ON books.category = categories.CategoryID 
                WHERE 1=1";
        
        //checks if title has a value
        if (!empty($title)) 
        {
            //this makes the search partial, rows with $title anywhere in the bookTitle will match.
            $sql .= " AND books.bookTitle LIKE '%$title%'";
        }
        //checks if author has a value
        if (!empty($author)) 
        {
            //rows that match author anywhere close to the title will pop up
            $sql .= " AND books.author LIKE '%$author%'";
        }
        //checks if category has a value
        if (!empty($category)) 
        {
            //shows books that match the category
            $sql .= " AND books.category = '$category'";
        }

        // count num results for pagination
        $count_sql = str_replace("books.*", "COUNT(*) AS total", $sql);
        $count_result = $conn->query($count_sql);
        $total_results = $count_result->fetch_assoc()['total'];

        $sql .= " LIMIT $items_per_page OFFSET $offset";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) 
        {
            echo "<h3>Search Results:</h3>";
            echo "<table border='1' cellpadding='10' cellspacing='0' >";
            echo "<tr><th>ISBN</th><th>Title</th><th>Author</th><th>Edition</th><th>Year</th><th>Category</th><th>Reserved</th><th>Reserve</th></tr>";

            //display results in table
            while ($row = $result->fetch_assoc()) {
                $isbn = htmlspecialchars($row['ISBN']);
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['ISBN']) . "</td>";
                echo "<td>" . htmlspecialchars($row['bookTitle']) . "</td>";
                echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                echo "<td>" . htmlspecialchars($row['edition']) . "</td>";
                echo "<td>" . htmlspecialchars($row['year']) . "</td>";
                echo "<td>" . htmlspecialchars($row['CategoryDescription']) . "</td>";
                echo "<td>" . htmlspecialchars($row['reserved']) . "</td>";
                echo "<td>";

                //if the book is reserved have reserved written in red
                if ($row['reserved'] === 'Y') 
                {
                    echo "<span style='color: red;'>Reserved</span>";
                } 
                else 
                {
                    //else, have a reserve button to allow the user to reserve the book
                    echo "<form method='POST' action='reservations.php'>
                            <input type='hidden' name='reserve_isbn' value='$isbn'>
                            <button type='submit'>Reserve</button>
                          </form>";
                }
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";

            // pagination
            $total_pages = ceil($total_results / $items_per_page);
            echo "<div class='custom-pagination'>";
            for ($i = 1; $i <= $total_pages; $i++) 
            {
                echo "<a href='search.php?page=$i&title=$title&author=$author&category=$category'";

                if ($i == $page) 
                {
                    echo " style='font-weight: bold; text-decoration: underline;'";
                }
                echo ">$i</a> ";
            }
            echo "</div>";
        } 
        else
        {
            echo "<p>No results found for your search.</p>";
        }
    }//end of first else on file
?>
</body>
</html>
