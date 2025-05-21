<!-- PHP Code -->
<?php
    // Start session
    session_start();

    // Include necessary files
    include("../Classes/connect.php");
    include("../Classes/functions.php");
    include("../Languages/translate.php");

    // Connect to the database
    $DB = new Database();

    // Handle lang switch
    if (isset($_GET['lang']) && in_array($_GET['lang'], ['en','pt'])) {
        $_SESSION['lang'] = $_GET['lang'];
        // Redirect back to the same page without the lang param
        $url = strtok($_SERVER['REQUEST_URI'], '?');
        header("Location: $url");
        exit;
    }

    // Fallback default
    if (!isset($_SESSION['lang'])) {
        $_SESSION['lang'] = 'en';
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Something was posted
        $email = $_POST['email'];
        $password = hash("sha256", $_POST['password']); // Hash the password

        if(!empty($email) && !empty($password)){
            // Read from database
            $query = "SELECT * FROM Users WHERE email='$email' AND password='$password'";
            $conn = $DB->connect();
            $result = mysqli_query($conn, $query); // return true or false if query worked or not
            if ($result){
                if($result && mysqli_num_rows($result) > 0){
                    $user_data = mysqli_fetch_assoc($result);
                    // Set session variables
                    if ($user_data['email'] == $email && $user_data['password'] == $password){
                        $_SESSION['user_id'] = $user_data['user_id'];
                        header("Location: ../Profile_Pages/index.php");
                        die;
                    }
                }
                // Invalid email or password
                $_SESSION['login_failed'] = true;
            }
        }
    }
?>



<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('CivicLink | Login') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../Styles/login_style.css">
</head>
<body>

    <!-- Login Form -->
    <div class="login-container">

        <!-- Title -->
        <h2><?= __('Login') ?></h2>
        <form action="login.php" method="post">

            <!-- Messages -->
            <?php
                // Check if signup was successful
                if (isset($_SESSION['signup_successful'])) {
                    echo "
                    <p class='success'>
                        <span class='material-symbols-outlined success-icon'>check_circle</span>
                        " . __('Signup successful!') . "
                    </p>";
                    unset($_SESSION['signup_successful']);
                }

                // Check if login failed
                if (isset($_SESSION['login_failed'])) {
                    echo "
                    <p class='error'>
                        <span class='material-symbols-outlined error-icon'>error_outline</span>
                        " . __('Invalid email or password.') . "
                    </p>";
                    unset($_SESSION['login_failed']);
                }
            ?>

            <!-- Email Inputs -->
            <div class="form-group">
                <label for="email" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                    <span class="material-symbols-outlined">mail</span>
                    <strong><?= __('Email') ?></strong>
                </label>
                <input 
                    type="text" 
                    id="email" 
                    name="email" 
                    placeholder="<?= __('Enter your email') ?>" 
                    required
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                >
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <label for="password" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                    <span class="material-symbols-outlined">lock</span>
                    <strong><?= __('Password') ?></strong>
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="<?= __('Enter your password') ?>" 
                    required
                >
            </div>

            <button type="submit" class="btn"><?= __('Log In') ?></button>
        </form>

        <!-- Footer Links -->
        <div class="footer">
            <p><?= __("Don't have an account? ") ?><a href="signup.php"><?= __('Sign up') ?></a></p>
            <p><a href="password_reset.php"><?= __('Forgot password?') ?></a></p>
                <a href="?lang=en">Switch to English?</a> |
                <a href="?lang=pt">Mudar para português?</a>
            </p>
        </div>
    </div>
</body>
</html>