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
        <link rel="stylesheet" href="style.css">
    </head>

    <style>        
        .information_section {
            margin-bottom: 20px;
        }
        
        .information_section strong {
            display: inline-block;
            width: 150px;
            color: #555;
        }

    </style>

    <body style="font-family: sans-serif; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- Submenu Button Area -->

            <!-- Add edit volunteer button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="volunteer_edit_data.php?id=<?php echo $id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Edit Data
                    </button>
                </a>
            </div>

            <!-- Add check button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="add_check.php?id=<?php echo $id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Add Check
                    </button>
                </a>
            </div>

            <!-- Add purchase button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="add_purchase.php?id=<?php echo $id; ?>" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Add Purchase
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

                <!-- Left area; Volunteer information area -->
                <div id="medium_rectangle" style="flex:0.7;">

                    <!-- Section title of contact section -->
                    <div id="section_title">
                        <span>Volunteer Info</span>
                    </div>

                    <!-- Personal Information -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Personal Information</h2>
                        <p><strong>First Name:</strong> <?php echo htmlspecialchars($member_data['first_name']); ?></p>
                        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($member_data['last_name']); ?></p>
                        <p><strong>Gender:</strong> <?php echo htmlspecialchars($member_data['gender']); ?></p>
                        <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($member_data['date_of_birth']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($member_data['address']); ?></p>
                        <p><strong>Zip Code:</strong> <?php echo htmlspecialchars($member_data['zip_code']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($member_data['telephone_number']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($member_data['email']); ?></p>
                    </div>
                    
                    <!-- Volunteer Contributions -->
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Volunteer Contributions</h2>
                        <p><strong>Points:</strong> <span><?php echo htmlspecialchars($member_data['points']); ?></span></p>
                        <p><strong>Hours Completed:</strong> <span><?php echo htmlspecialchars($member_data['hours_completed']); ?></span></p>
                        <p><strong>Assigned Area:</strong> <?php echo htmlspecialchars($member_data['assigned_area']); ?></p>
                        <p><strong>Organizer Name:</strong> <?php echo htmlspecialchars($member_data['organizer_name']); ?></p>
                    </div>

                    <!-- Interests -->
                    <div class="information_section" style="margin-bottom: 20px;">
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
                    <div class="information_section" style="margin-bottom: 20px;">
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
                        
                        <table style="width: 50%; border-collapse: collapse; margin-top: 10px;">
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
                    <div class="information_section" style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; color: #555;">Additional Details</h2>
                        <p><strong>Additional Notes:</strong> <?php echo htmlspecialchars($member_data['additional_notes']) ?: 'None'; ?></p>
                        <p><strong>Registration Date:</strong> <?php echo htmlspecialchars($member_data['registration_date']); ?></p>
                        <p><strong>Profile In Trash:</strong> <?php echo htmlspecialchars($member_data['trashed'] ? "Yes" : "No"); ?></p>
                    </div>
                    
                </div>

                <!-- Right area -->
                <div style="min-height: 400px; flex:1.5; padding-left: 20px; padding-right: 0px;">

                    <!-- Widget display -->
                    <div id="medium_rectangle">

                        <!-- Section title of recent social activities section -->
                        <div id="section_title">
                            <span>Volunteers</span>
                        </div>
                        
                        

                    </div>

                </div>

            </div>
            
        </div>
        
    </body>
</html>