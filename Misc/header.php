<div id="blue_bar">
    <div>
        <!-- Logo -->
        <!--<a href="../Profile_Pages/home.php" class="logo">Give and Receive</a> -->
        <span class="logo" style="font-weight: bold;">Scheduling System</span>

        <!-- Navigation Menu -->
        <div id="menu_container">
            <a href="../Listing_Pages/all_volunteers.php" class="menu_button">Volunteers</a>
            <a href="../Listing_Pages/all_activities.php" class="menu_button">Activities</a>
            <a href="../Listing_Pages/all_contracts.php" class="menu_button">Contracts</a>
            <a href="../Listing_Pages/all_purchases.php" class="menu_button">Purchases</a>
            <a href="../Profile_Pages/about.php" class="menu_button">About</a>
            <!--<a href="../Profile_Pages/contact.php" class="menu_button">Contact</a>-->
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
</style>