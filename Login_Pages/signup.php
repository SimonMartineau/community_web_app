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

    // Initialize error
    $error = [];

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Something was posted
        $email = $_POST['email'];
        $password = $_POST['password'];
    
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

                // Email is good, check password
                } if (strlen($password) < 8){
                    $error[] = "Password must be at least 8 characters long.";
                } if (!preg_match('/[A-Z]/', $password)) {
                    $error[] = "Password must contain at least one uppercase letter.";
                } if (!preg_match('/[a-z]/', $password)) {
                    $error[] = "Password must contain at least one lowercase letter.";
                } if (!preg_match('/[0-9]/', $password)) {
                    $error[] = "Password must contain at least one number.";
                } if (!preg_match('/[\W_]/', $password)) {
                    $error[] = "Password must contain at least one special character.";
                } if (empty($error) && $_SESSION['email_exists'] == false) {
                    // Password is valid and email does not exist, proceed to save to database
                    $user_id = random_num(20); // Generate a random user ID

                    // Hash the password
                    $password = hash("sha256", $_POST['password']);

                    // Check if the user ID already exists, if so, generate a new one
                    $query = "SELECT * FROM Users WHERE user_id='$user_id'";
                    $result_user_id = $DB->read($query);
                    while ($result_user_id) {
                        // If user ID already exists, generate a new one
                        $user_id = random_num(20);
                        $query = "SELECT * FROM Users WHERE user_id='$user_id'";
                        $result_user_id = $DB->read($query);
                    }

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
    <title><?= __('CivicLink | Signup') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../Styles/login_style.css">
</head>
<body>
    <!-- Signup Form -->
    <div class="login-container">

        <!-- Title -->
        <h2><?= __('Sign up') ?></h2>
        <form action="signup.php" method="post">

            <!-- Messages -->
            <?php
                // Check if signup was successful
                foreach ($error as $err){
                    echo "<div class='error'>" . __($err) . "</div>";
                }

                // Check if email already exists.
                if (!empty($_SESSION['email_exists'])) {
                    echo "
                    <p class='error'>
                        <span class='material-symbols-outlined error-icon'>error_outline</span>
                        " . __('An account already has that email.') . "
                    </p>";
                    unset($_SESSION['email_exists']);
                }

                // Check if email is invalid
                if (!empty($_SESSION['invalid_email'])) {
                    echo "
                    <p class='error'>
                        <span class='material-symbols-outlined error-icon'>error_outline</span>
                        " . __('Invalid email format.') . "
                    </p>";
                    unset($_SESSION['invalid_email']);
                }
            ?>

            <!-- Email Input -->
            <div class="form-group">
                <label for="email" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                    <span class="material-symbols-outlined">mail</span>
                    <strong><?= __('Email') ?></strong>
                </label>
                <input type="text" id="email" name="email" placeholder="<?= __('Enter your email') ?>" required
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <label for="password" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                    <span class="material-symbols-outlined">lock</span>
                    <strong><?= __('Password (min 8 characters)') ?></strong>
                </label>
                <input type="password" id="password" name="password" placeholder="<?= __('Enter your password') ?>" required>
            </div>

            <button type="submit" class="btn"><?= __('Sign Up') ?></button>
        </form>

        <!-- Footer Links -->
        <div class="footer">
            <p><?= __('Already have an account?') ?> <a href="login.php"><?= __('Login') ?></a></p>
            <p><a href="password_reset.php"><?= __('Forgot password?') ?></a></p>
            <p>
                <a href="?lang=en">Switch to English?</a> |
                <a href="?lang=pt">Mudar para português?</a>
            </p>
        </div>

    </div>
</body>
</html>