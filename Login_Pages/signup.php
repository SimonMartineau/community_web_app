<!-- PHP Code -->
<?php
    // Start session
    session_start();

    // Include necessary files
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Connect to the database
    $DB = new Database();

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Something was posted
        $email = $_POST['email'];
        $password = hash("sha256", $_POST['password']);

        if(!empty($email) && !empty($password)){
            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['invalid_email'] = true;
            } else {
                // Email is valid, proceed to check if it exists in the database
                $query = "SELECT * FROM Users WHERE email='$email'";
                $result = $DB->read($query);
                if($result){
                    // Tell the user if email already exists.
                    $_SESSION['email_exists'] = true;
                    
                } else{
                    // Email does not exist, proceed to save to database
                    $user_id = random_num(20); // Generate a random user ID

                    // SQL prepared statement into Users
                    $users_query = "INSERT INTO Users (user_id, email, password) VALUES (?, ?, ?)";
                    $types = "iss"; // Types of data to be inserted
                    $params = [$user_id, $email, $password]; // Parameters to be inserted
                    $DB->save_prepared($users_query, $types, $params);
                    $_SESSION['signup_successful'] = true;
                    // Redirect to login page
                    header("Location: login.php");
                    die;
                }
            }
        } else {
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
    <!-- Signup Form -->
    <div class="login-container">

        <!-- Title -->
        <h2>Sign up</h2>
        <form action="signup.php" method="post">
        
            <!-- Messages -->
            <?php
                // Check if email already exists.
                if (!empty($_SESSION['email_exists'])) {
                    echo "
                    <p class='error'>
                        <span class='material-symbols-outlined error-icon'>error_outline</span>
                        An account already has that email.
                    </p>";
                    unset($_SESSION['email_exists']);
                }

                // Check if email is invalid
                if (!empty($_SESSION['invalid_email'])) {
                    echo "
                    <p class='error'>
                        <span class='material-symbols-outlined error-icon'>error_outline</span>
                        Invalid email format.
                    </p>";
                    unset($_SESSION['invalid_email']);
                }
            ?>

            <!-- Email Input -->
            <div class="form-group">
                <label for="email"><span class="material-symbols-outlined">mail</span><strong> Email</strong></label>
                <input type="text" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <label for="password"><span class="material-symbols-outlined">lock</span><strong> Password</strong></label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Sign Up</button>
        </form>

        <!-- Footer Links -->
        <div class="footer">
            <p>Already have an account? <a href="login.php">Login</a></p>
            <p><a href="password_reset.php">Forgot password?</a></p>
        </div>
    </div>
</body>
</html>