<style>
    #blue_bar {
    height: 60px;
    background-color: #405d9b;
    color: #d9dfeb;
    display: flex;
    align-items: center;
    justify-content: center;
    }

    #blue_bar > div {
        width: 100%;
        max-width: 1400px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
        font-size: 20px;
    }

    /* Logo Link */
    #blue_bar a.logo {
        font-size: 24px;
        color: #d0d8e4;
        text-decoration: none;
        font-weight: bold;
    }

    #menu_buttons {
    width: auto;
    padding: 10px 20px;
    display: inline-block;
    margin: 0 10px;
    white-space: nowrap;
    text-decoration: none;
    font-size: 16px;
    background-color: #506db8;
    color: white;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    text-align: center;
    }

    #menu_buttons:hover {
        background-color: #6c85d3;
        color: #f0f4ff;
    }

    /* Navigation Menu */
    #menu_container {
        display: flex;
        justify-content: center;
        flex-grow: 1; /* Takes up remaining space for centering */
        gap: 20px; /* Adds spacing between buttons */
        position: absolute; /* Use absolute positioning for centering */
        left: 50%; /* Align center */
        transform: translateX(-50%); /* Adjust for true centering */
    }

    /* Logout Styling */
    #blue_bar a.logout {
        font-size: 14px;
        color: white;
        text-decoration: none;
        margin-left: auto;
        padding: 5px 10px;
        border: 1px solid white;
        border-radius: 5px;
        background-color: transparent;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    #blue_bar a.logout:hover {
        background-color: white;
        color: #405d9b;
    }
</style>


<div id="blue_bar">
    <div>
        <!-- Logo -->
        <a href="home.php" class="logo">Give and Receive</a>

        <!-- Navigation Menu -->
        <div id="menu_container">
            <a href="volunteers.php"><div id="menu_buttons">Volunteers</div></a>
            <a href="social_activities.php"><div id="menu_buttons">Social Activities</div></a>
            <a href="data.php"><div id="menu_buttons">Data</div></a>
            <a href="about.php"><div id="menu_buttons">About</div></a>
            <a href="contact.php"><div id="menu_buttons">Contact</div></a>
        </div>

        <!-- Logout -->
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>