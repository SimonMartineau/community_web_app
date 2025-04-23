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
            // Read from database
            $query = "select * from Users where email='$email' and password='$password'";
            $DB = new Database();
            $conn = $DB->connect();
            $result = mysqli_query($conn, $query); // return true or false if query worked or not
            if ($result){
                if($result && mysqli_num_rows($result) > 0){
                    $user_data = mysqli_fetch_assoc($result);
                    // Set session variables
                    if ($user_data['email'] == $email && $user_data['password'] == $password){
                        $_SESSION['user_id'] = $user_data['user_id'];
                        header("Location: ../Listing_Pages/all_volunteers.php");
                        die;
                    }
                }
            }
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
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../login_style.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="#" method="post">
            <div class="form-group">
                <label for="email"><span class="material-symbols-outlined">mail</span><strong> Email</strong></label>
                <input type="text" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password"><span class="material-symbols-outlined">lock</span><strong> Password</strong></label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Log In</button>
        </form>
        <div class="footer">
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
            <p><a href="password_reset.php">Forgot password?</a></p>
        </div>
    </div>
</body>
</html>