<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-
    ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
    crossorigin="anonymous">

    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <meta charset="UTF-8">
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <body>
            <nav class="navbar bg-body-tertiary">
            <a href="index.php">Home</a>
            <a href= "listOfBooks.php">list of books</a>
            <a href= "search.php">search</a>
            <a href = "logout.php">logout</a>
            </nav>
    <header>
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
        <div>
        <h1>Library</h1>
        <p>Find your next best read!</p>
        </div>
    </header>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDf
    L" crossorigin="anonymous">
    </script>

    <script src="javascript/script.js"></script>
    </body>
</head>

    <div style ="text-align:centre; padding:15%;">
    <p  style="font-size:50px; font-weight:bold;">
    </div>
</body>
<?php } ?>
<footer>
    <p> Created By Mera Saoud</p>
</footer>
</html>