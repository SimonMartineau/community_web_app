<!-- PHP Code -->
<?php
    // Start session
    session_start();

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/edit_volunteer_data.php");
    include("../Classes/functions.php");

    // Connect to the database
    $DB = new Database();
    // Check if user is logged in. If not, redirect to login page.
    $user_data = $DB->check_login();

    // Create a Edit_Activity object for form validation
    if (isset($_GET['volunteer_id'])) {
        $volunteer_id = $_GET['volunteer_id'];
    }

    // Fetch SQL data
    $volunteer_data_row = fetch_volunteer_data_row($volunteer_id);
    $interests_data_rows = fetch_volunteer_interest_data_rows($volunteer_id);
    $availability_data_rows = fetch_volunteer_availability_data_rows($volunteer_id);

    // Default entry values on page startup.
    $first_name = $volunteer_data_row['first_name'];
    $last_name = $volunteer_data_row['last_name'];
    $gender = $volunteer_data_row['gender'];
    $date_of_birth = $volunteer_data_row['date_of_birth'];
    $address = $volunteer_data_row['address'];
    $zip_code = $volunteer_data_row['zip_code'];
    $telephone_number = $volunteer_data_row['telephone_number'];
    $email = $volunteer_data_row['email'];
    // For interest data, we extract the interest column and insert the data in $volunteer_interests[].
    foreach($interests_data_rows as $interests_data_row){
        $volunteer_interests[] = $interests_data_row['interest'];
    }
    $volunteer_availability = [];
    // For availability data, we extract the weekday and timeperiod columns and insert the data in $volunteer_availability[].
    foreach($availability_data_rows as $availability_data_row){
        $weekday = $availability_data_row['weekday'];
        $time_period = $availability_data_row['time_period'];
        $available_moment = "{$weekday}-{$time_period}";
        $volunteer_availability[] = $available_moment;
    }
    $volunteer_manager = $volunteer_data_row['volunteer_manager'];
    $entry_clerk = $volunteer_data_row['entry_clerk'];
    $additional_notes = $volunteer_data_row['additional_notes'];
    
    // Check if user has submitted info, we update entries.
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Create a Edit_Volunteer object for form evaluation
        $volunteer = new Edit_Volunteer();
        $submit_success = $volunteer->evaluate($volunteer_id, $_POST); // Evaluate the form

        // If there are errors 
        if(!$submit_success){
            // Re-enter user input data in prompts
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            if(isset($_POST['gender'])){ // Due to bubble entry
                $gender = $_POST['gender'];
            }
            $date_of_birth = $_POST['date_of_birth'];
            $address = $_POST['address'];
            $zip_code = $_POST['zip_code'];
            $telephone_number = $_POST['telephone_number'];
            $email = $_POST['email'];
            if(isset($_POST['volunteer_availability'])){ // Due to table entry
                $volunteer_availability = $_POST['volunteer_availability'];
            } else{
                $volunteer_availability = [];
            }
            if(isset($_POST['volunteer_interests'])){ // Due to table entry
                $volunteer_interests = $_POST['volunteer_interests'];
            } else{
                $volunteer_interests = [];
            }
            $volunteer_manager = $_POST['volunteer_manager'];
            $entry_clerk = $_POST['entry_clerk'];
            $additional_notes = $_POST['additional_notes'];

        } else{ 
            // There are no errors with the form submit, we can change the page.
            header("Location: ../Profile_Pages/volunteer_profile.php?volunteer_id=" . $volunteer_id);
            die; // Ending the script
        }    
    }
?> 



<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Volunteer Data | Give and Receive</title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="../style.css">
    </head>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header Bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Middle Area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            
            <!-- Major Rectangle Area -->
            <div id="major_rectangle">
                
                <!-- Title -->
                <div id="section_title" style="margin-bottom: 20px;">
                    <span style="font-size: 24px; font-weight: bold;">Edit Volunteer Data</span>
                </div>

                <!-- Error Message -->
                <div style="text-align: center;">
                    <span id="main_error" style="color: red; font-weight: bold;">
                        <?php echo isset($submit_success) ? "Missing information. Could not send. Please try again." : ""; ?>
                    </span>
                </div>

                <!-- Form Area -->
                <form method="post" action="../Edit_Form_Pages/edit_volunteer_data.php?volunteer_id=<?php echo $volunteer_id; ?>" class="form-layout" form>

                    <!-- First Name Text Input -->
                    <div class="form-field">
                        <label for="first_name">
                            First Name:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter the volunteer's first name (ex: "John").
                            </span>
                        </label>
                        <input name="first_name" type="text" id="text_input" value="<?php echo $first_name ?>">
                        <span id="error_message"><?php echo isset($volunteer) ? $volunteer->first_name_error_mes : ''; ?></span>
                    </div>

                    <!-- Last Name Text Input -->
                    <div class="form-field">
                        <label for="last_name">
                            Last name:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter the volunteer's last name (ex: "Doe").
                            </span>
                        </label>
                        <input name="last_name" type="text" id="text_input" value="<?php echo $last_name ?>">
                        <span id="error_message"><?php echo isset($volunteer) ? $volunteer->last_name_error_mes : ''; ?></span>
                    </div>

                    <!-- Gender Bubble Contract -->
                    <div class="form-field">
                        <label for="purchase_date">
                            Gender:
                            <span class="tooltip">?
                                <span class="tooltip-text">Select the bubble that matches the volunteer's gender.
                            </span>
                        </label>
                        <div style="margin-bottom: 20px;">
                            <input type="radio" name="gender" value="Male" <?php echo ($gender == 'Male') ? 'checked' : ''; ?>> Male
                            <input type="radio" name="gender" value="Female" <?php echo ($gender == 'Female') ? 'checked' : ''; ?>> Female
                            <input type="radio" name="gender" value="Other" <?php echo ($gender == 'Other') ? 'checked' : ''; ?>> Other
                        </div>
                       <span id="error_message"><?php echo isset($volunteer) ? $volunteer->gender_error_mes : ''; ?></span>
                    </div>

                    <!-- Date of Birth Input -->
                    <div class="form-field">
                        <label for="date_of_birth">
                            Date of Birth: 
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter the volunteer's date of birth. Click the calendar icon to select a date.
                            </span>
                        </label>
                        <input type="date" name="date_of_birth" value="<?php echo $date_of_birth ?>">
                        <span id="error_message"><?php echo isset($volunteer) ? $volunteer->date_of_birth_error_mes : ''; ?></span>
                    </div>

                    <!-- Address Text Input -->
                    <div class="form-field">
                        <label for="address">
                            Address:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter the volunteer's address (ex: "123 Main St, City, Country").
                            </span>
                        </label>
                        <input name="address" type="text" id="text_input" value="<?php echo $address ?>">
                        <span id="error_message"><?php echo isset($volunteer) ? $volunteer->address_error_mes : ''; ?></span>
                    </div>

                    <!-- ZIP Code Text Input -->
                    <div class="form-field">
                        <label for="zip_code">
                            ZIP code:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter the volunteer's ZIP code (ex: "12345").
                            </span>
                        </label>
                        <input name="zip_code" type="text" id="text_input" value="<?php echo $zip_code ?>">
                        <span id="error_message"><?php echo isset($volunteer) ? $volunteer->zip_code_error_mes : ''; ?></span>
                    </div>

                    <!-- Telephone Number Text Input -->
                    <div class="form-field">
                        <label for="telephone_number">
                            Telephone number:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter the volunteer's telephone number (ex: "+123456789").
                            </span>
                        </label>
                        <input name="telephone_number" type="text" id="text_input" value="<?php echo $telephone_number ?>">
                        <span id="error_message"><?php echo isset($volunteer) ? $volunteer->telephone_number_error_mes : ''; ?></span>
                    </div>

                   <!-- Email Text Input -->
                    <div class="form-field">
                        <label for="email">
                            Email:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter the volunteer's email address (ex: johndoe@gmail.com).
                            </span>
                        </label>
                        <input name="email" type="text" id="text_input" value="<?php echo $email ?>">
                        <span id="error_message"><?php echo isset($volunteer) ? $volunteer->email_error_mes : ''; ?></span>
                    </div>

                    <!-- Volunteer's Interests Table -->
                    <div class="form-field form-field-top">
                        <label for="volunteer_interests">
                            Volunteer's Interests 
                            <span class="tooltip">?
                                <span class="tooltip-text">Select the activities the volunteer is interested in doing.
                            </span>
                        </label>
                        <div class="form-checkbox-group">
                            <?php
                            // List of activities
                            $activities = [
                                "Organization of community events", 
                                "Library support", 
                                "Help in the community store", 
                                "Support in the community grocery store", 
                                "Cleaning and maintenance of public spaces", 
                                "Participation in urban gardening projects"
                            ];

                            // Create vertical list with checkboxes on the left
                            foreach ($activities as $activity) {
                                $checked = in_array($activity, $volunteer_interests) ? "checked" : "";
                                echo "<div class='form-checkbox-item'>";
                                echo "<input type='checkbox' name='volunteer_interests[]' value='$activity' $checked>";
                                echo "<label style='margin: 0;'>$activity</label>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <span id="error_message"><?php echo isset($volunteer) ? $volunteer->volunteer_interests_error_mes : ''; ?></span>                    
                    </div>

                    <!-- Volunteer Availability Text Input -->
                    <div class="form-field form-field-top">
                        <label for="volunteer_availability">
                            Weekly Availability: 
                            <span class="tooltip">?
                                <span class="tooltip-text">Select the weekdays and time periods when the volunteer is available.
                            </span>
                        </label>
                        <div style="text-align: center;">
                            <table border="1" style="border-collapse: collapse; text-align: center; width: 50%; margin-left: auto; margin-right: auto;">
                                <tr>
                                    <th>Day</th>
                                    <th>Morning</th>
                                    <th>Afternoon</th>
                                    <th>Evening</th>
                                </tr>
                                <?php
                                
                                // List of days and time periods
                                $week = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
                                $time_periods = ["Morning", "Afternoon", "Evening"];
                                
                                // Display days and checkboxes
                                foreach ($week as $weekday) {
                                    echo "<tr>";
                                    echo "<td><strong>$weekday</strong></td>";
                                    foreach ($time_periods as $time_period){
                                        $available_moment = "{$weekday}-{$time_period}";
                                        if (in_array($available_moment, $volunteer_availability)){
                                            echo "<td><input type='checkbox' name='volunteer_availability[]' value=$available_moment checked></td>";
                                        } else {
                                            echo "<td><input type='checkbox' name='volunteer_availability[]' value=$available_moment></td>";
                                        }
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </table>
                        </div>
                        <span id="error_message"><?php echo isset($volunteer) ? $volunteer->volunteer_availability_error_mes : ''; ?></span>
                    </div>

                    <!-- Volunteer Manager Text Input -->
                    <div class="form-field">
                        <label for="volunteer_manager">
                            Volunteer Manager:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter the name of the volunteer manager (ex: "James Stewart").
                            </span>
                        </label>
                        <input name="volunteer_manager" type="text" id="text_input" value="<?php echo $volunteer_manager ?>">
                        <span id="error_message"><?php echo isset($volunteer) ? $volunteer->volunteer_manager_error_mes : ''; ?></span>
                    </div>

                    <!-- Entry Clerk Text Input -->
                    <div class="form-field">
                        <label for="entry_clerk">
                            Entry Clerk:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter the name of the person filling out this form (ex: "Jane Smith").
                            </span>
                        </label>
                        <input name="entry_clerk" type="text" id="text_input" value="<?php echo $entry_clerk ?>">
                        <span id="error_message"><?php echo isset($volunteer) ? $volunteer->entry_clerk_error_mes : ''; ?></span>
                    </div>

                    <!-- Additional Notes Text Input -->
                    <div class="form-field form-field-top">
                        <label for="additional_notes">
                            Additional Notes:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter any additional notes or comments about the volunteer. This field is optional.
                            </span>
                        </label>                   
                        <textarea name="additional_notes" rows="10" cols="60" id="additional_notes" placeholder="(Optional)"><?php echo $additional_notes ?></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="input_container">
                        <input type="submit" id="submit_button" value="Submit">
                    </div>

                </form>

            </div>
        </div>
        
    </body>
</html>