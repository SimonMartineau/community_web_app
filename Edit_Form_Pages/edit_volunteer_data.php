<!-- PHP Code -->
<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/edit_volunteer_data.php");
    include("../Classes/functions.php");

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
    $registration_date = $volunteer_data_row['registration_date'];

    
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
            $registration_date = $_POST['registration_date'];


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
                <div id="form_section">

                    <!-- Form Text Input -->
                    <form method="post" action="../Edit_Form_Pages/edit_volunteer_data.php?volunteer_id=<?php echo $volunteer_id; ?>">

                        <!-- First Name Text Input -->
                        <div class="input_container">
                            First name:
                            <input name="first_name" type="text" id="text_input" value="<?php echo $first_name ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->first_name_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Last Name Text Input -->
                        <div class="input_container">
                            Last name:
                            <input name="last_name" type="text" id="text_input" value="<?php echo $last_name ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->last_name_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Gender Bubble Contract -->
                        <div class="input_container">
                            Gender:
                            <input type="radio" name="gender" value="Male" <?php echo ($gender == 'Male') ? 'checked' : ''; ?>> Male
                            <input type="radio" name="gender" value="Female" <?php echo ($gender == 'Female') ? 'checked' : ''; ?>> Female
                            <input type="radio" name="gender" value="Other" <?php echo ($gender == 'Other') ? 'checked' : ''; ?>> Other
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->gender_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Date of Birth Input -->
                        <div class="input_container">
                            Date of birth: <input type="date" name="date_of_birth" value="<?php echo $date_of_birth ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->date_of_birth_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Address Text Input -->
                        <div class="input_container">
                            Address:
                            <input name="address" type="text" id="text_input" value="<?php echo $address ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->address_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- ZIP Code Text Input -->
                        <div class="input_container">
                            ZIP code:
                            <input name="zip_code" type="text" id="text_input" value="<?php echo $zip_code ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->zip_code_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Telephone Number Text Input -->
                        <div class="input_container">
                            Telephone number:
                            <input name="telephone_number" type="text" id="text_input" value="<?php echo $telephone_number ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->telephone_number_error_mes : ''; ?></span>
                        </div>
                        <br><br>
                        
                        <!-- Email Text Input -->
                        <div class="input_container">
                            Email:
                            <input name="email" type="text" id="text_input" value="<?php echo $email ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->email_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Volunteer's Interests Table -->
                        <div class="input_container">
                            <h4 style="text-align: center;">Volunteer's Interests</h4> 
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->volunteer_interests_error_mes : ''; ?></span>
                        </div>
                        <div style="text-align: center;">
                            <table border="1" style="border-collapse: collapse; text-align: center; width: 50%; margin-left: auto; margin-right: auto;">
                                <tr>
                                    <th>Activity</th>
                                    <th>Contract</th>
                                </tr>
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

                                // Create table with checkboxes
                                foreach ($activities as $activity) {
                                    echo "<tr>";
                                    echo "<td>$activity</td>";
                                    if (in_array($activity, $volunteer_interests)){
                                        echo "<td><input type='checkbox' name='volunteer_interests[]' value='$activity' checked></td>";
                                    } else {
                                        echo "<td><input type='checkbox' name='volunteer_interests[]' value='$activity'></td>";
                                    }                                    
                                    echo "</tr>";
                                }
                                ?>
                            </table>
                        </div>
                        <br>

                        <!-- Volunteer Availability Text Input -->
                        <div class="input_container">
                            <h4 style="text-align: center;">Weekly Availability</h4>
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->volunteer_availability_error_mes : ''; ?></span>
                        </div>
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

                                // Create table with checkboxes
                                foreach ($week as $weekday) {
                                    echo "<tr>";
                                    echo "<td>$weekday</td>";
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
                        <br>

                        <!-- Volunteer Manager Text Input -->
                        <div class="input_container">
                            Volunteer manager:
                            <input name="volunteer_manager" type="text" id="text_input" value="<?php echo $volunteer_manager ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->volunteer_manager_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Entry Clerk Text Input -->
                        <div class="input_container">
                            Entry clerk:
                            <input name="entry_clerk" type="text" id="text_input" value="<?php echo $entry_clerk ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->entry_clerk_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Additional Notes Text Input -->
                        <div style="text-align: center">
                            Additional Notes:
                            <br>
                            <textarea name="additional_notes" rows="10" cols="60" id="additional_notes" placeholder="(Optional)"><?php echo $additional_notes ?></textarea>
                        </div>
                        <br><br>

                        <!-- Registration Date Text Input -->
                        <div class="input_container">
                            Registration date:
                            <input name="registration_date" type="date" id="text_input" value="<?php echo $registration_date ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->registration_date_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Submit Button -->
                        <div class="input_container">
                            <input type="submit" id="submit_button" value="Submit">
                        </div>
                        <br><br>
                        
                    </form>
                </div>

            </div>
        </div>
        
    </body>
</html>