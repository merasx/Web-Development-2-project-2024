<?php
session_start();
include 'connection.php';

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
    else { 
$sql = "SELECT * FROM books";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Book List</h2>";
    echo "<table border='1' cellpadding='10' cellspacing='0' id='booksTable'>";
    echo "<tr>
            <th>ISBN</th>
            <th>Book Title</th>
            <th>Author</th>
            <th>Edition</th>
            <th>Year</th>
            <th>Category</th>
            <th>Reserved</th>
            <th>Actions</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        $isbn = htmlspecialchars($row["ISBN"]);
        echo "<tr id='row-$isbn'>";
        echo "<td>" . htmlspecialchars($row["ISBN"]) . "</td>";
        echo "<td id='title-$isbn'>" . htmlspecialchars($row["BookTitle"]) . "</td>";
        echo "<td id='author-$isbn'>" . htmlspecialchars($row["Author"]) . "</td>";
        echo "<td id='edition-$isbn'>" . htmlspecialchars($row["Edition"]) . "</td>";
        echo "<td id='year-$isbn'>" . htmlspecialchars($row["Year"]) . "</td>";
        echo "<td id='category-$isbn'>" . htmlspecialchars($row["Category"]) . "</td>";
        echo "<td id='reserved-$isbn'>" . htmlspecialchars($row["Reserved"]) . "</td>";
        echo "<td>";
        echo "<button onclick='deleteBook(\"$isbn\")'>Delete</button> ";
        echo "<button onclick='openUpdateForm(\"$isbn\")'>Update</button>";
        echo "</td>";
        echo "</tr>";

        echo "<tr id='update-row-$isbn' style='display: none;'>";
        echo "<td colspan='8'>";
        echo "<form onsubmit='updateBook(event, \"$isbn\")'>";
        echo "Title: <input type='text' id='update-title-$isbn' value='" . htmlspecialchars($row["bookTitle"]) . "' required> ";
        echo "Author: <input type='text' id='update-author-$isbn' value='" . htmlspecialchars($row["author"]) . "' required> ";
        echo "Edition: <input type='number' id='update-edition-$isbn' value='" . htmlspecialchars($row["edition"]) . "' required> ";
        echo "Year: <input type='number' id='update-year-$isbn' value='" . htmlspecialchars($row["year"]) . "' required> ";
        echo "Category: <input type='text' id='update-category-$isbn' value='" . htmlspecialchars($row["category"]) . "' required> ";
        echo "Reserved: <select id='update-reserved-$isbn' required>
                    <option value='Y' " . ($row["Reserved"] == 'Y' ? "selected" : "") . ">Yes</option>
                    <option value='N' " . ($row["Reserved"] == 'N' ? "selected" : "") . ">No</option>
                  </select> ";
        echo "<button type='submit'>Save</button> ";
        echo "<button type='button' onclick='closeUpdateForm(\"$isbn\")'>Cancel</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No books available right now. Please check back later.</p>";
}

$conn->close();
?>

<script>
function deleteBook(isbn) {
    if (confirm("Are you sure you want to delete this book?")) {
        fetch('delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'isbn=' + encodeURIComponent(isbn)
        })
        .then(response => response.text())
        .then(result => {
            alert(result);
            if (result.includes("successfully")) {
                document.getElementById("row-" + isbn).remove();
                document.getElementById("update-row-" + isbn).remove();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function openUpdateForm(isbn) {
    document.getElementById("update-row-" + isbn).style.display = "table-row";
}

function closeUpdateForm(isbn) {
    document.getElementById("update-row-" + isbn).style.display = "none";
}

function updateBook(event, isbn) {
    event.preventDefault();

    const title = document.getElementById("update-title-" + isbn).value;
    const author = document.getElementById("update-author-" + isbn).value;
    const edition = document.getElementById("update-edition-" + isbn).value;
    const year = document.getElementById("update-year-" + isbn).value;
    const category = document.getElementById("update-category-" + isbn).value;
    const reserved = document.getElementById("update-reserved-" + isbn).value;

    fetch('update.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `isbn=${encodeURIComponent(isbn)}&title=${encodeURIComponent(title)}&author=${encodeURIComponent(author)}&edition=${edition}&year=${year}&category=${encodeURIComponent(category)}&reserved=${reserved}`
    })
    .then(response => response.text())
    .then(result => {
        alert(result);
        if (result.includes("successfully")) {
            document.getElementById("title-" + isbn).innerText = title;
            document.getElementById("author-" + isbn).innerText = author;
            document.getElementById("edition-" + isbn).innerText = edition;
            document.getElementById("year-" + isbn).innerText = year;
            document.getElementById("category-" + isbn).innerText = category;
            document.getElementById("reserved-" + isbn).innerText = reserved;

            closeUpdateForm(isbn);
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
<?php } ?>
