<!-- PHP Code -->
<?php
    // Start session
    session_start();

    // Include necessary files
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Connect to the database
    $DB = new Database();
    // Check if user is logged in. If not, redirect to login page.
    $user_data = $DB->check_login();
?>

<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact | Give and Receive</title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="../style.css">
    </head>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header Bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Cover Area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">

            <!-- Contact Content Area -->
            <div id="major_rectangle">
                <!-- Section Title of Contact Section -->
                <div id="section_title" style="margin-bottom: 20px;">
                    <span style="font-size: 24px; font-weight: bold;">Contact Us</span>
                </div>

                <p style="line-height: 1.6; font-size: 16px; color: #333;">
                    We're here to help! If you have any questions, feedback, or need assistance using <strong>Give and Receive</strong>, don’t hesitate to get in touch with us. Our team is dedicated to ensuring your experience on the platform is seamless and fulfilling.
                </p>

                <h3 style="margin-top: 20px; color: #405d9b;">How to Reach Us</h3>
                <ul style="line-height: 1.8; font-size: 16px; margin-left: 20px; color: #333;">
                    <li><strong>Email Support:</strong> Send us an email at <a href="mailto:support@giveandreceive.com" style="color: #405d9b; text-decoration: none;">support@giveandreceive.com</a>. We aim to respond within 24 hours.</li>
                    <li><strong>Phone:</strong> Call us at <strong>(555) 123-4567</strong>. Our support lines are open Monday to Friday, 9 AM to 5 PM.</li>
                    <li><strong>Contact Form:</strong> Use the contact form below to send us your queries or feedback. Simply fill in the required details and hit "Submit."</li>
                </ul>
            </div>
        </div>
            
        
    </body>
</html>