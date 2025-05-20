<!-- PHP Code -->
<?php
    // Start session
    session_start();

    // Include necessary files
    include("../Classes/connect.php");
    include("../Classes/functions.php");
    include("../Languages/translate.php");

    require "mail.php";

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

// Set default mode
$mode = "enter_email"; // Default mode
if (isset($_GET['mode'])) {
    $mode = $_GET['mode'];
}

// Something is posted
if (count($_POST) > 0) {
    switch ($mode) {
        case "enter_email":
            // Change to enter code page
            $email = $_POST['email'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error[] = __("Invalid email format.");
            } elseif (!valid_email($DB, $email)) {
                $error[] = __("Email not found.");
            } else {
                $_SESSION['forgot']['email'] = $email; // To use later
                prepare_email($DB, $email);
                header("Location: password_reset.php?mode=enter_code");
                die;
            }
            break;

        case "enter_code":
            $code = $_POST['code'];
            $result = is_code_correct($DB, $code);

            if ($result == "The code is correct.") {
                $_SESSION['forgot']['code'] = $code;
                header("Location: password_reset.php?mode=enter_password");
                die;
            } else {
                $error[] = __($result);
            }
            break;

        case "enter_password":
            $password = $_POST['new_password'];
            $password2 = $_POST['new_password2'];

            if (strlen($password) < 8) {
                $error[] = __("Password must be at least 8 characters.");
            } elseif (!preg_match('/[A-Z]/', $password)) {
                $error[] = __("Password must contain at least one uppercase letter.");
            } elseif (!preg_match('/[a-z]/', $password)) {
                $error[] = __("Password must contain at least one lowercase letter.");
            } elseif (!preg_match('/[0-9]/', $password)) {
                $error[] = __("Password must contain at least one number.");
            } elseif (!preg_match('/[\W_]/', $password)) {
                $error[] = __("Password must contain at least one special character.");
            } elseif ($password != $password2) {
                $error[] = __("Passwords do not match.");
            } elseif (!isset($_SESSION['forgot']['email']) || !isset($_SESSION['forgot']['code'])) {
                header("Location: password_reset.php?mode=enter_email");
                die;
            } else {
                save_password($DB, $password);
                unset($_SESSION['forgot']);
                header("Location: login.php");
                die;
            }
            break;
    }
}


    function prepare_email($DB, $email){
        $expire = time() + (60*5);
        $code = rand(10000, 99999);
        $query = "INSERT INTO Forgot_Password (email, code, expire) VALUES ('$email', '$code', '$expire')";
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
    <title><?= __('CivicLink | Password Reset') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../Styles/login_style.css">
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
                    <h2><?= __('Lost Your Password?') ?></h2>

                    <form action="password_reset.php?mode=enter_email" method="post">

                        <!-- Messages -->
                        <?php 
                            foreach ($error as $err){
                                echo "<div class='error'>" . __($err) . "</div>";
                            }
                        ?>

                        <!-- Email Input -->
                        <div class="form-group">
                            <label for="email" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span class="material-symbols-outlined">mail</span>
                                <strong><?= __('Email') ?></strong>
                            </label>
                            <input type="text" id="email" name="email" placeholder="<?= __('Enter your email') ?>" required>
                        </div>

                        <button type="submit" class="btn"><?= __('Get New Password') ?></button>
                    </form>

                    <!-- Footer Links -->
                    <div class="footer">
                        <p><?= __('Already have an account?') ?> <a href="login.php"><?= __('Login') ?></a></p>
                        <p>
                            <a href="?lang=en">Switch to English?</a> |
                            <a href="?lang=pt">Mudar para português?</a>
                        </p>
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
                    <h2><?= __('Enter the code') ?></h2>

                    <form action="password_reset.php?mode=enter_code" method="post">

                        <!-- Messages -->
                        <?php 
                            foreach ($error as $err){
                                echo "<div class='error'>" . __($err) . "</div>";
                            }
                        ?>

                        <!-- Code Input -->
                        <div class="form-group">
                            <label for="code" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span class="material-symbols-outlined">mail</span>
                                <strong><?= __('Code') ?></strong>
                            </label>
                            <input type="text" id="code" name="code" placeholder="<?= __('Enter the code') ?>" required>
                        </div>

                        <button type="submit" class="btn"><?= __('Submit') ?></button>
                    </form>

                    <!-- Footer Links -->
                    <div class="footer">
                        <p><?= __('Already have an account?') ?> <a href="login.php"><?= __('Login') ?></a></p>
                        <p>
                            <a href="?lang=en">Switch to English?</a> |
                            <a href="?lang=pt">Mudar para português?</a>
                        </p>
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
                    <h2><?= __('New password') ?></h2>

                    <form action="password_reset.php?mode=enter_password" method="post">

                        <!-- Messages -->
                        <?php 
                            foreach ($error as $err){
                                echo "<div class='error'>" . __($err) . "</div>";
                            }
                        ?>

                        <!-- New Password Input -->
                        <div class="form-group">
                            <label for="new_password" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span class="material-symbols-outlined">lock</span>
                                <strong><?= __('Enter your new password') ?></strong>
                            </label>
                            <input type="password" id="new_password" name="new_password" placeholder="<?= __('Enter your new password') ?>" required>
                            <br><br>

                            <label for="new_password2" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span class="material-symbols-outlined">lock</span>
                                <strong><?= __('Retype your new password') ?></strong>
                            </label>
                            <input type="password" id="new_password2" name="new_password2" placeholder="<?= __('Retype your new password') ?>" required>
                        </div>

                        <button type="submit" class="btn"><?= __('Submit') ?></button>
                    </form>

                    <!-- Footer Links -->
                    <div class="footer">
                        <p><?= __('Already have an account?') ?> <a href="login.php"><?= __('Login') ?></a></p>
                        <p>
                            <a href="?lang=en">Switch to English?</a> |
                            <a href="?lang=pt">Mudar para português?</a>
                        </p>
                    </div>
                </div>

                <?php
                break;
        }

    ?>

</body>
</html>