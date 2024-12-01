<?php
include 'connection.php';

    // Handle Registration
    if (isset($_POST['signUp'])) 
    {
        $username = $_POST['username'];
        $password = ($_POST['password'];
        $firstName = $_POST['firstName'];
        $surName = $_POST['surName'];
        $addressLine1 = $_POST['addressLine1'];
        $addressLine2 = $_POST['addressLine2'];
        $city = $_POST['city'];
        $telephone = $_POST['telephone'];
        $mobile = $_POST['mobile'];

        // Check if username already exists
        $checkUsername = "SELECT * FROM users WHERE username='$username'";
        $result = $conn->query($checkUsername);
        if ($result->num_rows > 0) 
        {
            echo "Username Already Exists";
        } 
        else 
        {
            // Insert new user
            $sql = "INSERT INTO users (username, password, firstName, surName, addressLine1, addressLine2, city, telephone, mobile) 
                    VALUES ('$username', '$password', '$firstName', '$surName', '$addressLine1', '$addressLine2', '$city', '$telephone', '$mobile')";
            if ($conn->query($sql) === TRUE) 
            {
                echo "New user registered successfully!";
                header("Location: index.php");
                exit();
            } 
            else 
            {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    // Handle Login
    if (isset($_POST['signIn'])) 
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query to check if the user exists
        $sql = "SELECT * FROM users WHERE username = '$username' and password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) 
        {
            session_start();
            $row=$result->fetch_assoc();
            $_SESSION['username']=$row['username'];
            header("Location: index.php");
            exit();
        } 
        else 
        {
            echo "Incorrect password.";
        }
    }

?>
