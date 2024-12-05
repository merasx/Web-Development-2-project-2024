<?php 
session_start();
include_once "connection.php";

// Unset any previous session data
unset($_SESSION["username"]);

if (isset($_POST["username"]) && isset($_POST["password"])) {   
    // Escape user inputs to prevent SQL injection
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    
    // Fetch the user with the given username
    $sql = "SELECT username, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    // Check if a user exists with that username
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Compare the password
        if ($password === $row['password']) {
            // Correct login
            $_SESSION["username"] = $username;
            $_SESSION["success"] = "Logged in.";
            header( 'Location: index.php' ) ;
            return;
        } else {
            // Incorrect password
            $_SESSION["error"] = "Incorrect password.";
            header('Location: login.php');
            return;
        }
    } else {
        // Username doesn't exist
        $_SESSION["error"] = "User doesn't exist.";
        header('Location: login.php');
        return;
    }
} else if (count($_POST) > 0) { 
    // Missing required information
    $_SESSION["error"] = "Missing Required Information";
    header('Location: login.php');
    return;
}
?>
<html>
<head>
<link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body style="font-family: sans-serif;">
<header>
<h1>Please Log In</h1>
</header>
<?php
if (isset($_SESSION["error"])) {
    echo('<p style="color:red">Error: ' . $_SESSION["error"] . "</p>\n");
    unset($_SESSION["error"]);
}
?>
<form method="post">
<p>Username: <input type="text" name="username" value=""></p>
<p>Password: <input type="password" name="password" value=""></p>
<p><input type="submit" value="Log In"></p>
<p>dont have an account? <a href="userReg.php">register here!</a></p>
</form>
</body>
</html>
