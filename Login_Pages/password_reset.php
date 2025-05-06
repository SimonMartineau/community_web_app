<!-- PHP Code -->
<?php
    // Start session
    session_start();

    // Include necessary files
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    require "mail.php";

    // Connect to the database
    $DB = new Database();

    // Initialize error
    $error = [];

    // Set default mode
    $mode = "enter_email"; // Default mode
    if (isset($_GET['mode'])) {
        $mode = $_GET['mode'];
    }

    // Something is posted
    if (count($_POST) > 0){
        switch ($mode) {
            case "enter_email":
                // Change to enter code page
                $email = $_POST['email'];
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    // Invalid email format
                    $error[] = "Invalid email format.";
                } elseif (!valid_email($DB, $email)){
                    // Email not found
                    $error[] = "Email not found.";
                } else{
                    $_SESSION['forgot']['email'] = $email; // To use later
                    prepare_email($DB, $email);
                    header("Location: password_reset.php?mode=enter_code");
                    die;
                }
                break;
                
            case "enter_code":
                $code = $_POST['code'];
                $result = is_code_correct($DB, $code);

                if ($result == "The code is correct."){
                    // Change to enter new password page
                    $_SESSION['forgot']['code'] = $code;
                    header("Location: password_reset.php?mode=enter_password");
                    die;
                } else {
                    $error[] = $result;
                }
                break;
                
            case "enter_password":                    
                // Change to login page
                $password = $_POST['new_password'];
                $password2 = $_POST['new_password2'];

                if (strlen($password) < 8){
                    // Password too short
                    $error[] = "Password must be at least 8 characters.";
                } elseif (!preg_match('/[A-Z]/', $password)){
                    // Password must contain at least one uppercase letter
                    $error[] = "Password must contain at least one uppercase letter.";
                } elseif (!preg_match('/[a-z]/', $password)){
                    // Password must contain at least one lowercase letter
                    $error[] = "Password must contain at least one lowercase letter.";
                } elseif (!preg_match('/[0-9]/', $password)){
                    // Password must contain at least one number
                    $error[] = "Password must contain at least one number.";
                } elseif (!preg_match('/[\W_]/', $password)){
                    // Password must contain at least one special character
                    $error[] = "Password must contain at least one special character.";
                } elseif ($password != $password2){
                    // Passwords do not match
                    $error[] = "Passwords do not match.";
                    // Security check to make sure the user is not trying to access this page directly
                } elseif(!isset($_SESSION['forgot']['email']) || !isset($_SESSION['forgot']['code'])){
                    // Redirect to enter email page
                    header("Location: password_reset.php?mode=enter_email");
                    die;
                } else{
                    save_password($DB, $password);
                    // Clear session
                    if (isset($_SESSION['forgot'])){
                        unset($_SESSION['forgot']);
                    }
                    header("Location: login.php");
                    die;
                }
        }
            
    }


    function prepare_email($DB, $email){
        $expire = time() + (60*5);
        $code = rand(10000, 99999);
        $query = "INSERT INTO Forgot_Password (email, code, expire) value ('$email', '$code', '$expire')";
        $DB -> save($query);

        // Send email here
        send_email($email, "Password Reset Code", "Your password reset code is: " . $code);
    }

    function is_code_correct($DB, $code){
        $code = addslashes($code);
        $expire = time();
        $email = addslashes($_SESSION['forgot']['email']);
        $query = "SELECT * FROM Forgot_Password WHERE email='$email' AND code='$code' ORDER BY expire DESC LIMIT 1";
        $result = $DB -> base_query($query);
        if($result){
            if (mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
                if ($row['expire'] > $expire){
                    return "The code is correct.";
                } else{
                    return "The code is expired.";
                }
            } else{
                "The code is incorrect.";
            }
        }
        return "The code is incorrect.";
    }

    function save_password($DB, $password){
        $email = addslashes($_SESSION['forgot']['email']);
        $password = hash("sha256", $password);
        $query = "UPDATE Users SET password='$password' WHERE email='$email'";
        $DB -> save($query);
        return true;
    }

    function valid_email($DB, $email){
        $email = addslashes($email);
        $query = "SELECT * FROM Users WHERE email='$email'";
        $result = $DB -> base_query($query);
        if($result){
            if (mysqli_num_rows($result) > 0){
                return true;
            }
        }
        return false;
    }

    
?>



<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give and Receive</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../login_style.css">
</head>
<body>
    <?php 
        switch ($mode) {
            case "enter_email":
                // HTML Code
                ?>

                <!-- Enter Email Form -->
                <div class="login-container">

                    <!-- Title -->
                    <h2>Lost Your Password?</h2>
                    <form action="password_reset.php?mode=enter_email" method="post">
                    
                        <!-- Messages -->
                        <?php 
                            foreach ($error as $err){
                                echo "<div class='error'>$err</div>";
                            }
                        ?>

                        <!-- Email Input -->
                        <div class="form-group">
                            <label for="email" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span class="material-symbols-outlined">mail</span>
                                <strong>Email</strong>
                            </label>
                            <input type="text" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <button type="submit" class="btn">Get New Password</button>
                    </form>

                    <!-- Footer Links -->
                    <div class="footer">
                        <p>Already have an account? <a href="login.php">Login</a></p>
                    </div>
                </div>

                <?php
                break;

            case "enter_code":
                // HTML Code
                ?>

                <!-- Enter Code Form -->
                <div class="login-container">

                    <!-- Title -->
                    <h2>Enter the code</h2>
                    <form action="password_reset.php?mode=enter_code" method="post">
                    
                        <!-- Messages -->
                        <?php 
                            foreach ($error as $err){
                                echo "<div class='error'>$err</div>";
                            }
                        ?>

                        <!-- Code Input -->
                        <div class="form-group">
                            <label for="email" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span class="material-symbols-outlined">mail</span>
                                <strong>Code</strong>
                            </label>
                            <input type="text" id="code" name="code" placeholder="Enter the code" required>
                        </div>
                        <button type="submit" class="btn">Submit</button>
                    </form>

                    <!-- Footer Links -->
                    <div class="footer">
                        <p>Already have an account? <a href="login.php">Login</a></p>
                    </div>
                </div>

                <?php
                break;
            case "enter_password":
                // HTML Code
                ?>

                <!-- New Password Form -->
                <div class="login-container">

                    <!-- Title -->
                    <h2>New password</h2>
                    <form action="password_reset.php?mode=enter_password" method="post">
                    
                        <!-- Messages -->
                        <?php 
                            foreach ($error as $err){
                                echo "<div class='error'>$err</div>";
                            }
                        ?>

                        <!-- New Password Input -->
                        <div class="form-group">
                            <label for="email" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span class="material-symbols-outlined">mail</span>
                                <strong>Enter your new password</strong>
                            </label>
                            <input type="password" id="password" name="new_password" placeholder="Enter your new password" required>
                            <br><br>
                            
                            <label for="email" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span class="material-symbols-outlined">mail</span>
                                <strong>Retype your new password</strong>
                            </label>
                            <input type="password" id="password" name="new_password2" placeholder="Retype your new password" required>
                        
                        </div>
                        <button type="submit" class="btn">Submit</button>
                    </form>

                    <!-- Footer Links -->
                    <div class="footer">
                        <p>Already have an account? <a href="login.php">Login</a></p>
                    </div>
                </div>

                <?php
                break;
        }

    ?>


    
</body>
</html>