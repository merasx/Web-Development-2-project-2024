<?php 
session_start();

//database connection
include_once "connection.php";

// unset any previous session data
unset($_SESSION["username"]);

//if the user submitted their username and password
if (isset($_POST["username"]) && isset($_POST["password"])) 
{   
    //get data, real_escape_string() makes sure that special characters in $username and $password are safely escaped
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    
    // find the user with the username entered
    $sql = "SELECT username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    // checks if a user exists with that username
    if ($result && $result->num_rows > 0) 
    {
        $row = $result->fetch_assoc();

        // checks if the password matches the password in the database table
        if ($password === $row['password']) 
        {
            // logs user in and assigns the session variable to their username
            $_SESSION["username"] = $username;
            $_SESSION["success"] = "Logged in successfully";

            //redirect to index.php
            header( 'Location: index.php' ) ;
            return;
        }
        else 
        {
            // incorrect password
            $_SESSION["error"] = "Incorrect password.";

            //restarts the form
            header('Location: login.php');
            return;
        }

    } 
    else 
    {
        // if the username doesn't exist, assign an error message 
        $_SESSION["error"] = "User doesn't exist.";

        //restart the form
        header('Location: login.php');
        return;
    }
} 
?>
<!--html form for login-->
<html>
<head>
<!--link to bootstrap-->
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-
    ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
    crossorigin="anonymous">
<link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body style="font-family: sans-serif;">
<header>
<h1>Please Log In</h1>
</header>
<?php
//display an error message if any problems occured with logging in
if (isset($_SESSION["error"])) {
    echo('<p style="color:red">Error: ' . $_SESSION["error"] . "</p>\n");
    unset($_SESSION["error"]);
}
?>
<form method="post">
<p>Username: <input type="text" name="username" value=""></p>
<p>Password: <input type="password" name="password" value=""></p>
<p><input type="submit" value="Log In"></p>

<!-- redirect user to user registration page if they dont have an account-->
<p>dont have an account? <a href="userReg.php">register here!</a></p>
</form>
</body>
</html>
