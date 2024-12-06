<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--link to bootstrap-->
    <link rel="stylesheet"
     href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-
    ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
    crossorigin="anonymous">
    
    <!--link to css-->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <meta charset="UTF-8">
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    
    <body>
            <nav class="navbar bg-body-tertiary">
            <a href="index.php">Home</a>
            <a href="listOfReservations.php">My Reservations</a>
            <a href= "search.php">search</a>
            <a href = "logout.php">logout</a>
            </nav>
    <header>
    <?php

    //if user fails to login display error message in red
    if ( isset($_SESSION["error"]) ) 
    {
    echo('<p style="color:rgb(204,0,0);">Error:'.$_SESSION["error"]."</p>\n");
    unset($_SESSION["error"]);
    }

    //if user succeeds display success message "logged in successfully" in green
    if ( isset($_SESSION["success"]) ) 
    {
    echo('<p style="color: rgb(51,102,0);">'.$_SESSION["success"]."</p>\n");
    unset($_SESSION["success"]);
    }
    
    //if users not logged in, link to login page
    if ( ! isset($_SESSION["username"]) ) 
    { 
        ?>
        <h2>Please <a href="login.php">Log In</a> to start</h2>
        <div style ="text-align:centre; padding:19%;">
        <p  style="font-size:50px; font-weight:bold;">
        </div>
        <?php 
    }
    else //display index page
    { ?>
        <div>
        <h1>Mera's Library</h1>
        <p>Find your next best read!</p>
        </div>
    </header>
    <!-- about Library section -->
    <section class="container text-center my-5">
            <h2>About our Library</h2>
            <p>Welcome to my online library, where an amazing world of books awaits you. From thrilling adventures to captivating non-fiction, our diverse collection spans all genres. We're certain that your next best read is just a search away!</p>
    </section>
        
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