<?php
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

// Determine current language (fallback to English)
$lang = $_SESSION['lang'] ?? 'en';
?>


<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CivicLink | Home</title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="../Styles/header_style.css">
    </head>

    <body>
        <div id="blue_bar">
            <div>
                <!-- Logo -->
                <a href="../Profile_Pages/index.php" class="logo">CivicLink</a>

                <!-- Navigation Menu -->
                <div id="menu_container">
                    <a href="../Profile_Pages/index.php" class="menu_button">Home</a>
                    <a href="../Listing_Pages/all_volunteers.php" class="menu_button">Volunteers</a>
                    <a href="../Listing_Pages/all_activities.php" class="menu_button">Activities</a>
                    <a href="../Listing_Pages/all_contracts.php" class="menu_button">Contracts</a>
                    <a href="../Listing_Pages/all_purchases.php" class="menu_button">Purchases</a>
                    <a href="../Profile_Pages/about.php" class="menu_button">About</a>
                    <!--<a href="../Profile_Pages/contact.php" class="menu_button">Contact</a>-->
                </div>

                <!-- Language Dropdown -->
                <div class="dropdown">
                    <button class="dropbtn">
                        <span class="flag"><?= $lang === 'pt' ? '🇵🇹' : '🇬🇧' ?></span>
                        <?= $lang === 'pt' ? 'Português' : 'English' ?>
                    </button>
                    <div class="dropdown-content">
                        <form method="post">
                            <input type="hidden" name="lang" value="en">
                            <button type="submit"><span class="flag">🇬🇧</span> English</button>
                        </form>
                        <form method="post">
                            <input type="hidden" name="lang" value="pt">
                            <button type="submit"><span class="flag">🇵🇹</span> Português</button>
                        </form>
                    </div>
                </div>

                <!-- Logout Button -->
                <a href="../Login_Pages/logout.php" class="logout">Logout</a>
        
            </div>
        </div>

        <script>
        (function() {
            const bar = document.getElementById('blue_bar');
            bar.style.position = 'relative';

            let targetX = 0;    // where we want to go
            let currentX = 0;   // where we are now
            const ease = 0.1;   // lower = more smoothing

            function animate() {
            // Update the target each frame
            targetX = window.scrollX || window.pageXOffset;

            // Ease currentX toward targetX
            currentX += (targetX - currentX) * ease;

            // Apply the transform
            bar.style.transform = `translateX(${currentX}px)`;

            // Loop
            requestAnimationFrame(animate);
            }

            // Kick it off
            animate();
        })();
        </script>

    </body>