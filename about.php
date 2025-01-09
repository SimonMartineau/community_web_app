<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>About | Give and Receive</title>
    </head>

    <style>
        #post_bar{
            margin-top: 20px;
            background-color: white;
            padding: 10px;
            min-height: 400px; 
            flex:1.5; 
            padding-left: 20px; 
            padding-right: 0px;
        }

        #section_title {
        text-align: center; /* Center the title */
        margin: 20px 0; /* Add space above and below */
        font-family: Arial, sans-serif; /* Use a clean font */
        }

        #section_title span {
            font-size: 1.2em; /* Larger font size for emphasis */
            font-weight: bold; /* Make the text bold */
            color: #405d9b; /* Theme color for text */
            padding: 10px 20px; /* Add some padding around the text */
            background: linear-gradient(to right, #f0f8ff, #dbe9f9); /* Subtle gradient background */
            border-radius: 10px; /* Rounded corners for the background */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            display: inline-block; /* Ensure the background fits tightly */
        }

    </style>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- About content area -->
            <div id="post_bar" style="padding: 20px; background-color: #f9f9f9; border-radius: 8px;">
                <!-- Section title of about section -->
                <div id="section_title" style="margin-bottom: 20px;">
                    <span style="font-size: 24px; font-weight: bold;">About</span>
                </div>

                <p style="line-height: 1.6; font-size: 16px; color: #333;">
                    Welcome to <strong>Give and Receive</strong>, a platform designed to connect individuals looking to make a difference in their communities. Whether you want to volunteer, participate in social activities, or simply explore opportunities to contribute, this website has everything you need.
                </p>

                <h3 style="margin-top: 20px; color: #405d9b;">How to Use the Website</h3>
                <ul style="line-height: 1.8; font-size: 16px; margin-left: 20px; color: #333;">
                    <li><strong>Volunteers Page:</strong> 
                        Visit the <a href="volunteers.php" style="color: #405d9b; text-decoration: none;">Volunteers</a> page to view a list of volunteers in your community. You can filter volunteers by their interests, availability, or other criteria.
                    </li>
                    <li><strong>Social Activities Page:</strong> 
                        Explore the <a href="social_activities.php" style="color: #405d9b; text-decoration: none;">Social Activities</a> page to find events and initiatives in your area. You can check event details, including duration, location, and participant count.
                    </li>
                    <li><strong>Add a Volunteer:</strong> 
                        If you’re organizing a community initiative and need volunteers, you can use the <a href="add_volunteer_page.php" style="color: #405d9b; text-decoration: none;">Add Volunteer</a> button to register new volunteers.
                    </li>
                    <li><strong>Filter Options:</strong> 
                        Use the filter feature to find specific volunteers or activities that match your preferences, such as interests, gender, or availability.
                    </li>
                    <li><strong>About Us:</strong> 
                        Learn more about the mission and goals of this platform in the <a href="about.php" style="color: #405d9b; text-decoration: none;">About</a> section.
                    </li>
                </ul>

                <h3 style="margin-top: 20px; color: #405d9b;">Why Use This Platform?</h3>
                <p style="line-height: 1.6; font-size: 16px; color: #333;">
                    Our mission is to simplify community engagement and encourage collaboration among individuals and organizations. Whether you're passionate about helping in a community store, participating in urban gardening projects, or organizing public cleanups, this platform makes it easy to connect and contribute.
                </p>

                <h3 style="margin-top: 20px; color: #405d9b;">Need Help?</h3>
                <p style="line-height: 1.6; font-size: 16px; color: #333;">
                    If you have any questions or need assistance, feel free to contact us through the <a href="contact.php" style="color: #405d9b; text-decoration: none;">Contact</a> page. We're here to help!
                </p>
            </div>

            
        </div>
        
    </body>
</html>