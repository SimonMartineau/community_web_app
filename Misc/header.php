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
echo $_SESSION['lang'];
?>


<div id="blue_bar">
    <div>
        <!-- Logo -->
        <!--<a href="../Profile_Pages/home.php" class="logo">Give and Receive</a> -->
        <span class="logo" style="font-weight: bold;">Volunteer Management</span>

        <!-- Navigation Menu -->
        <div id="menu_container">
            <a href="../Listing_Pages/all_volunteers.php" class="menu_button">Volunteers</a>
            <a href="../Listing_Pages/all_activities.php" class="menu_button">Activities</a>
            <a href="../Listing_Pages/all_contracts.php" class="menu_button">Contracts</a>
            <a href="../Listing_Pages/all_purchases.php" class="menu_button">Purchases</a>
            <a href="../Profile_Pages/about.php" class="menu_button">About</a>
            <!--<a href="../Profile_Pages/contact.php" class="menu_button">Contact</a>-->
        </div>

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



<!-- CSS Styling -->
<style>
    #blue_bar {
        position: relative;
        left: 0;
        height: 60px;
        z-index: 1000;
        background-color: #405d9b;
        color: #d9dfeb;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        will-change: transform;
    }

    #blue_bar > div {
        width: 100%;
        max-width: 1400px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
        font-size: 20px;
        gap: 15px; /* Added gap for spacing */
    }

    /* Logo Link */
    #blue_bar span.logo {
        font-size: 24px;
        color: #d0d8e4;
        text-decoration: none;
        font-weight: bold;
        white-space: nowrap; /* Prevent logo wrapping */
    }

    .menu_button {
        padding: 10px 15px;
        display: inline-block;
        white-space: nowrap;
        text-decoration: none;
        font-size: 1rem;
        background-color: #506db8;
        color: white;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        text-align: center;
    }

    .menu_button:hover {
        background-color: #6c85d3;
        color: #f0f4ff;
    }

    /* Navigation Menu */
    #menu_container {
        display: flex;
        justify-content: center;
        flex-grow: 1;
        gap: 40px;
        overflow-x: auto;
    }

    /* Logout Styling */
    #blue_bar a.logout {
        font-size: 14px;
        color: white;
        text-decoration: none;
        padding: 5px 10px;
        border: 1px solid white;
        border-radius: 5px;
        background-color: transparent;
        transition: background-color 0.3s ease, color 0.3s ease;
        white-space: nowrap; /* Prevent logout wrapping */
    }

    #blue_bar a.logout:hover {
        background-color: white;
        color: #405d9b;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        #blue_bar > div {
            padding: 0 15px;
        }
        
        .menu_button {
            padding: 8px 12px;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 992px) {
        #menu_container {
            gap: 8px;
        }
        
        .menu_button {
            padding: 6px 10px;
            font-size: 0.85rem;
        }
        
        #blue_bar a.logo {
            font-size: 20px;
        }
    }

    @media (max-width: 768px) {
        #blue_bar > div {
            flex-wrap: wrap;
            height: auto;
            padding: 10px 15px;
        }
        
        #menu_container {
            order: 3;
            width: 100%;
            justify-content: flex-start;
            margin-top: 10px;
            padding: 5px 0;
        }
    }

    .dropbtn {
        background-color: #506db8;
        color: white;
        border: none;
        cursor: pointer;
        padding: 10px 15px;
        display: inline-block;
        white-space: nowrap;
        text-decoration: none;
        font-size: 1rem;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        text-align: center;
        min-width: 130px;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 130px;
        box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
        z-index: 1;
        font-size: 1rem;
    }

    /* target both links and buttons */
    .dropdown-content button {
        display: block;           /* full‐width clickable area */
        width: 100%;
        padding: 12px 16px;
        text-align: left;
        text-decoration: none;    /* remove underline from links */
        background: none;         /* remove default button bg */
        border: none;             /* remove default button border */
        color: black;
        cursor: pointer;
        font: inherit;            /* inherit font settings */
    }

    /* hover state */
    .dropdown-content button:hover {
        background-color: #f1f1f1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropbtn {
        background-color: #6c85d3;
    }
</style>