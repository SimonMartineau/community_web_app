<?php
    // Start session
    session_start();
    
    // Include classes
    include(__DIR__ . "/../Languages/translate.php");

    // Handle a POSTed language choice
    if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['lang'])
        && in_array($_POST['lang'], ['en','pt'], true)
    ) {
        $_SESSION['lang'] = $_POST['lang'];
        // Redirect to self to clear POST data (and keep URL clean)
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    // Determine current language (fallback to Portugese)
    $_SESSION['lang'] = $_SESSION['lang'] ?? 'pt';
    $lang = $_SESSION['lang'];
?>


<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="/CivicLink_Web_App/Styles/header_style.css">
    </head>

    <body>
        <div id="blue_bar">
            <div>
                <!-- Logo -->
                <a href="/CivicLink_Web_App/index.php" class="logo">CivicLink</a>

                <!-- Navigation Menu -->
                <div id="menu_container">
                    <a href="/CivicLink_Web_App/index.php" class="menu_button"><?= __('Home') ?></a>
                    <a href="/CivicLink_Web_App/Listing_Pages/all_volunteers.php" class="menu_button"><?= __('Volunteers') ?></a>
                    <a href="/CivicLink_Web_App/Listing_Pages/all_activities.php" class="menu_button"><?= __('Activities') ?></a>
                    <a href="/CivicLink_Web_App/Listing_Pages/all_contracts.php" class="menu_button"><?= __('Contracts') ?></a>
                    <a href="/CivicLink_Web_App/Listing_Pages/all_purchases.php" class="menu_button"><?= __('Purchases') ?></a>
                    <a href="/CivicLink_Web_App/Profile_Pages/about.php" class="menu_button"><?= __('About') ?></a>
                </div>

                <!-- Language Dropdown -->
                <div class="dropdown">
                    <button class="dropbtn">
                        <span class="flag"><?= $_SESSION['lang'] === 'pt' ? '🇵🇹' : '🇬🇧' ?></span>
                        <?= $_SESSION['lang'] === 'pt' ? __('Português') : __('English') ?>
                    </button>
                    
                    <div class="dropdown-content">
                        <form method="post">
                            <input type="hidden" name="lang" value="en">
                            <button type="submit">
                                <span class="flag">🇬🇧</span> <?= __('English') ?>
                            </button>
                        </form>
                        <form method="post">
                            <input type="hidden" name="lang" value="pt">
                            <button type="submit">
                                <span class="flag">🇵🇹</span> <?= __('Português') ?>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Logout Button -->
                <a href="/CivicLink_Web_App/Login_Pages/logout.php" class="logout"><?= __('Logout') ?></a>

            </div>
        </div>

        

    </body>