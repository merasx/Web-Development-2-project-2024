<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" id="signup">
        <h1 class="form-title">Register</h1>
        <form method="post" action ="registerhandler.php">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" id="username" name="username" placeholder="username" required>
                <label for="username">Username</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="password" required>
                <label for="password">Password</label>
            </div>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" id="fName" name="fName" placeholder="First Name" required>
                <label for="fName">First Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" id="lName" name="lName" placeholder="Last Name" required>
                <label for="lName">Last Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="text" id="addressLine1" name="addressLine1" placeholder="Address Line 1" required>
                <label for="addressLine1">Address Line 1</label>
            </div>
            <div class="input-group">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" id="addressLine2" name="addressLine2" placeholder="Address Line 2">
                <label for="addressLine2">Address Line 2</label>
            </div>
            <div class="input-group">
                <i class="fas fa-city"></i>
                <input type="text" id="city" name="city" placeholder="City" required>
                <label for="city">City</label>
            </div>
            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" id="telephone" name="telephone" placeholder="Telephone" required>
                <label for="telephone">Telephone</label>
            </div>
            <div class="input-group">
                <i class="fas fa-mobile-alt"></i>
                <input type="text" id="mobile" name="mobile" placeholder="Mobile" required>
                <label for="mobile">Mobile</label>
            </div>
            <input type="submit" class="btn" value="Sign Up" name="signUp">
        </form>
    </div>
</body>
</html>
