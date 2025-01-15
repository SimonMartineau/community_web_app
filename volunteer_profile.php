<?php

    // Include classes
    include("classes/connect.php");
    include("classes/volunteer_functions.php");

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $member_data = fetch_member_data($id);
        $interest_data = fetch_member_interest_data($id);
        $availability_data = fetch_member_availability_data($id);
    }

    // Add list of purchases, checks, activities done (other pages) and doing in this month, availability and interests

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Volunteer Profile | Give and Receive</title>
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


        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section strong {
            display: inline-block;
            width: 150px;
            color: #555;
        }
        .highlight {
            font-weight: bold;
            color: #007BFF;
        }

        #submenu_button{
            padding: 10px 20px; 
            background-color: #405d9b; 
            color: white; 
            border: none; 
            border-radius: 15px; 
            font-size: 16px; 
            cursor: pointer;
        }

    </style>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- Add edit volunteer button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="volunteer_edit_data.php?id=<?php echo $member_data[0]['id']; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Edit Data
                    </button>
                </a>
            </div>

            <!-- Add purchase button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="purchase.php" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        All Corresponding Activities
                    </button>
                </a>
            </div>

            <!-- Add check button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="check.php" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Add Check
                    </button>
                </a>
            </div>

            <!-- All checks button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="check.php" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        All Checks
                    </button>
                </a>
            </div>

            <!-- Add purchase button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="purchase.php" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Add Purchase
                    </button>
                </a>
            </div>

            <!-- All purchases button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="purchase.php" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        All Purchases
                    </button>
                </a>
            </div>

            <!-- Add delete volunteer button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="purchase.php" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Delete Volunteer
                    </button>
                </a>
            </div>
                    
            <!-- Below cover area -->
            <div style="display: flex;">

                <!-- Contact content area -->
                <div id="post_bar" style="padding: 20px; background-color: #f9f9f9; border-radius: 8px;">

                    <!-- Section title of contact section -->
                    <div id="section_title" style="margin-bottom: 20px;">
                        <span style="font-size: 24px; font-weight: bold;">Volunteer Info</span>
                    </div>

                    <!-- Personal Information -->
                    <div class="info-section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Personal Information</h2>
                        <p><strong>First Name:</strong> <?php echo htmlspecialchars($member_data[0]['first_name']); ?></p>
                        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($member_data[0]['last_name']); ?></p>
                        <p><strong>Gender:</strong> <?php echo htmlspecialchars($member_data[0]['gender']); ?></p>
                        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($member_data[0]['date_of_birth']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($member_data[0]['address']); ?></p>
                        <p><strong>Zip Code:</strong> <?php echo htmlspecialchars($member_data[0]['zip_code']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($member_data[0]['telephone_number']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($member_data[0]['email']); ?></p>
                    </div>
                    
                    <!-- Volunteer Contributions -->
                    <div class="info-section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Volunteer Contributions</h2>
                        <p><strong>Points:</strong> <span><?php echo htmlspecialchars($member_data[0]['points']); ?></span></p>
                        <p><strong>Hours Completed:</strong> <span><?php echo htmlspecialchars($member_data[0]['hours_completed']); ?></span></p>
                        <p><strong>Assigned Area:</strong> <?php echo htmlspecialchars($member_data[0]['assigned_area']); ?></p>
                        <p><strong>Organizer Name:</strong> <?php echo htmlspecialchars($member_data[0]['organizer_name']); ?></p>
                    </div>
                    
                    <!-- Interests -->
                    <div class="info-section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Interests</h2>
                        <?php if (!empty($interest_data)): ?>
                            <ul style="list-style-type: disc; padding-left: 20px;">
                                <?php foreach ($interest_data as $interest): ?>
                                    <li><?php echo htmlspecialchars($interest['interest'] ?: 'No specific interest provided'); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No interests provided.</p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Weekly Availability -->
                    <div class="info-section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Weekly Availability</h2>
                        <?php
                        // Define the weekdays and time periods
                        $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        $time_periods = ['Morning', 'Afternoon', 'Evening'];
                        
                        // Create a matrix for availability
                        $availability_matrix = [];
                        foreach ($weekdays as $weekday) {
                            foreach ($time_periods as $time_period) {
                                $availability_matrix[$weekday][$time_period] = '';
                            }
                        }
                        
                        // Populate the matrix based on availability_data
                        foreach ($availability_data as $availability) {
                            $weekday = $availability['weekday'];
                            $time_period = $availability['time_period'];
                            if (isset($availability_matrix[$weekday][$time_period])) {
                                $availability_matrix[$weekday][$time_period] = '✔';
                            }
                        }
                        ?>
                        
                        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                            <thead>
                                <tr style="background-color: #f1f1f1;">
                                    <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">Weekday</th>
                                    <?php foreach ($time_periods as $time_period): ?>
                                        <th style="padding: 8px; border: 1px solid #ddd; text-align: left;"><?php echo htmlspecialchars($time_period); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($weekdays as $weekday): ?>
                                    <tr>
                                        <td style="padding: 8px; border: 1px solid #ddd;"><?php echo htmlspecialchars($weekday); ?></td>
                                        <?php foreach ($time_periods as $time_period): ?>
                                            <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                                                <?php echo htmlspecialchars($availability_matrix[$weekday][$time_period]); ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Additional Details -->
                    <div class="info-section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Additional Details</h2>
                        <p><strong>Additional Notes:</strong> <?php echo htmlspecialchars($member_data[0]['additional_notes']) ?: 'None'; ?></p>
                        <p><strong>Registration Date:</strong> <?php echo htmlspecialchars($member_data[0]['registration_date']); ?></p>
                    </div>
                    
                </div>
            </div>
            
        </div>
        
    </body>
</html>