<!-- PHP Code -->
<?php
    // Start session
    session_start();

    include("../Classes/connect.php");
    include("../Classes/functions.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        // something was posted
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(!empty($email) && !empty($password)){
            // Save to database
            $user_id = random_num(20);
            $query = "insert into Users (user_id, email, password) values ('$user_id','$email', '$password')";
            $DB = new Database();
            $DB->save($query);
            // Redirect to login page
            header("Location: login.php");
            die;
        } else{
            echo "Please enter valid information";
        }
    }
?>



<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../login_style.css">
</head>
<body>
    <div class="login-container">
        <h2>Sign up</h2>
        <form action="#" method="post">
            <div class="form-group">
                <label for="email"><span class="material-symbols-outlined">mail</span><strong> Email</strong></label>
                <input type="text" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password"><span class="material-symbols-outlined">lock</span><strong> Password</strong></label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Sign Up</button>
        </form>
        <div class="footer">
            <p>Already have an account? <a href="login.php">Login</a></p>
            <p><a href="password_reset.php">Forgot password?</a></p>
        </div>
    </div>
</body>
</html>