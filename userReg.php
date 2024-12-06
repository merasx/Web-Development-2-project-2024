<?php 
session_start();

//database connection
include_once "connection.php";

// if the form is submitted
if (isset($_POST['register'])) 
{
    // get data
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $surName = $conn->real_escape_string($_POST['surName']);
    $addressLine1 = $conn->real_escape_string($_POST['addressLine1']);
    $addressLine2 = $conn->real_escape_string($_POST['addressLine2']);
    $city = $conn->real_escape_string($_POST['city']);
    $telephone = $conn->real_escape_string($_POST['telephone']);
    $mobile = $conn->real_escape_string($_POST['mobile']);
    
    // check if the username exists in database already
    $checkUsername = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($checkUsername);
    
    if ($result->num_rows > 0) 
    {
        // if the username exists- show an error message
        $_SESSION["error"] = "Username already exists. Please choose a different username.";
    } 
    else 
    {
        // insert users data into database
        $sql = "INSERT INTO users (username, password, firstName, surName, addressLine1, addressLine2, city, telephone, mobile)
                VALUES ('$username', '$password', '$firstName', '$surName', '$addressLine1', '$addressLine2', '$city', '$telephone', '$mobile')";
        
        //if the query is successful
        if ($conn->query($sql) === TRUE) 
        {
            // log user in (assign their username to the session variable) and redirect them to index.php
            $_SESSION["username"] = $username;
            $_SESSION["success"] = "Registration successful! You are now logged in.";
            header("Location: index.php");
            exit;  
        } 
        else 
        {
            // if query fails
            $_SESSION["error"] = "Error: " . $conn->error;
        }
    }
}
?>
<!-- html form for registering an account -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
      <!--link to bootstrap-->
    <link rel="stylesheet"
     href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-
    ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
    crossorigin="anonymous">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body style="font-family: sans-serif;">
    <header>
        <h1>Register an Account</h1>
    </header>

    <?php
    // display error message if user registration fails
    if (isset($_SESSION["error"])) {
        echo('<p style="color:red">' . $_SESSION["error"] . "</p>");
        unset($_SESSION["error"]);
    }
    ?>

    <!--html for the registration form -->
    <form method="post" action="userReg.php">
        <p>Username: <input type="text" name="username" required></p>
        <p>Password: <input type="password" name="password" required></p>
        <p>First Name: <input type="text" name="firstName" required></p>
        <p>Last Name: <input type="text" name="surName" required></p>
        <p>Address Line 1: <input type="text" name="addressLine1" required></p>
        <p>Address Line 2: <input type="text" name="addressLine2"></p>
        <p>City: <input type="text" name="city" required></p>
        <p>Telephone: <input type="text" name="telephone" required></p>
        <p>Mobile: <input type="text" name="mobile" required></p>
        
        <!--submit button-->
        <p><input type="submit" value="Register" name="register"></p>
    </form>

    <!--link to the login page if the user already has an account-->
    <p>Already have an account? <a href="login.php">Log in here</a></p>
</body>
</html>
