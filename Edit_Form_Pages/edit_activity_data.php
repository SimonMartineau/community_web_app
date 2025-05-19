<!-- PHP Code -->
<?php
    // Include header
    include("../Misc/header.php");

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/edit_activity_data.php");
    include("../Classes/functions.php");

    // Connect to the database
    $DB = new Database();
    // Check if user is logged in. If not, redirect to login page.
    $user_data = $DB->check_login();
    $user_id = $user_data['user_id'];

    // Get activity_id from the URL
    if (isset($_GET['activity_id'])) {
        $activity_id = $_GET['activity_id'];
    }

    // Fetch SQL data
    // Collecting activity data (only 1 row needed)
    $activity_data_row = fetch_data_rows(
        "SELECT * FROM Activities
                WHERE id = '$activity_id'"
    )[0];

    // Collecting activity time periods data
    $activity_time_periods_data_rows = fetch_data_rows(
        query: "SELECT * FROM Activity_Time_Periods
                WHERE activity_id = '$activity_id'
                AND user_id = '$user_id'"
    );

    // Collecting activity domains data
    $activity_domain_data_rows = fetch_data_rows(
        "SELECT * FROM Activity_Domains
                WHERE activity_id = '$activity_id'
                AND user_id = '$user_id'"
    );

    // Variables to keep user input data if failed submit
    $activity_name = $activity_data_row['activity_name'];
    $activity_duration = $activity_data_row['activity_duration'];
    $activity_location = $activity_data_row['activity_location'];
    $number_of_places = $activity_data_row['number_of_places'];
    $activity_date = $activity_data_row['activity_date'];
    // For activity_time_periods_data_rows, we extract the time_period column and insert the data in $activity_time_periods[].
    foreach($activity_time_periods_data_rows as $activity_time_periods_data_row){
        $activity_time_periods[] = $activity_time_periods_data_row['time_period'];
    }
    // For activity_domain_data, we extract the domain column and insert the data in $activity_domains[].
    foreach($activity_domain_data_rows as $activity_domain_data_row){
        $activity_domains[] = $activity_domain_data_row['domain'];
    }
    $entry_clerk = $activity_data_row['entry_clerk'];
    $additional_notes = $activity_data_row['additional_notes'];
    
    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Create a Edit_Activity object for form evaluation
        $activity = new Edit_Activity($user_id);
        $submit_success = $activity->evaluate($activity_id, $_POST); // Evaluate the form

        // If there are errors 
        if(!$submit_success){
            // Re-enter user input data in prompts
            $activity_name = $_POST['activity_name'];
            $activity_duration = $_POST['activity_duration'];
            $activity_location = $_POST['activity_location'];
            $number_of_places = $_POST['number_of_places'];
            $activity_date = $_POST['activity_date'];
            if(isset($_POST['activity_time_periods'])){ // Due to table entry
                $activity_time_periods = $_POST['activity_time_periods'];
            } else{
                $activity_time_periods = [];
            }
            if(isset($_POST['activity_domains'])){ // Due to table entry
                $activity_domains = $_POST['activity_domains'];
            } else{
                $activity_domains = [];
            }
            $entry_clerk = $_POST['entry_clerk'];
            $additional_notes = $_POST['additional_notes'];
        } else{
            // There are no errors with the form submit, we can change the page.
            header("Location: ../Profile_Pages/activity_profile.php?activity_id=" . $activity_id);
            die; // Ending the script
        }    
    }
?> 



<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CivicLink | Edit Activity</title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="../Styles/style.css">
    </head>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Middle Area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            
            <!-- Major Rectangle Area -->
            <div id="major_rectangle">

                <!-- Title -->
                <div id="section_title" style="margin-bottom: 20px;">
                    <span style="font-size: 24px; font-weight: bold;">Edit Activity Form</span>
                </div>

                <!-- Error Message -->
                <div style="text-align: center;">
                    <span id="main_error" style="color: red; font-weight: bold;">
                        <?php echo isset($submit_success) ? "Missing information. Could not send. Please try again." : ""; ?>
                    </span>
                </div>

                <!-- Form Area -->
                <form method="post" action="../Edit_Form_Pages/edit_activity_data.php?activity_id=<?php echo $activity_id; ?>" class="form-layout" form>

                    <!-- Activity Name Text Input -->
                    <div class="form-field">
                        <label for="activity_name">
                            Activity Name:
                            <span class="hint">?
                                <span class="hint-text">Enter the name of the activity (ex: "Community Cleanup").
                            </span>
                        </span>
                        </label>
                        <input name="activity_name" type="text" id="text_input" value="<?php echo $activity_name ?>">
                        <span id="error_message"><?php echo isset($activity) ? $activity->activity_name_error_mes : ''; ?></span>
                    </div>

                    <!-- Activity Number of Places Text Input -->
                    <div class="form-field">
                        <label for="number_of_places">
                            Number of Places:
                            <span class="hint">?
                                <span class="hint-text">Enter the number of places available for this activity (ex: 10).
                            </span>
                        </span>
                        </label>
                        <input name="number_of_places" type="text" id="text_input" value="<?php echo $number_of_places ?>">
                        <span id="error_message"><?php echo isset($activity) ? $activity->number_of_places_error_mes : ''; ?></span>
                    </div>

                    <!-- Activity Duration Text Input -->
                    <div class="form-field">
                        <label for="activity_duration">
                            Activity Duration:
                            <span class="hint">?
                                <span class="hint-text">Enter the duration (in hours) of the activity (ex: 3).
                            </span>
                        </label>
                        <input name="activity_duration" type="text" id="text_input" value="<?php echo $activity_duration ?>">
                        <span id="error_message"><?php echo isset($activity) ? $activity->activity_duration_error_mes : ''; ?></span>
                    </div>

                    <!-- Activity Location Text Input -->
                    <div class="form-field">
                        <label for="activity_location">
                            Activity Location:
                            <span class="hint">?
                                <span class="hint-text">Enter the location of the activity (ex: "Miratejo"). This field is optional.
                            </span>
                        </label>
                        <input name="activity_location" type="text" id="text_input" placeholder="(Optional)" value="<?php echo $activity_location ?>">
                    </div>

                    <!-- Dates Input -->
                    <div class="form-field">
                        <label for="activity_dates">
                            Activity Date:
                            <span class="hint">?
                                <span class="hint-text">Enter the date to edit when this activity takes place. Select the calendar icon to choose a date.
                            </span>
                        </label>
                        <input type="date" name="activity_date" value="<?php echo $activity_date ?>">
                        <span id="error_message"><?php echo isset($activity) ? $activity->activity_date_error_mes : ''; ?></span>
                    </div>

                    <!-- Activity Time Period Table -->
                    <div class="form-field form-field-top">
                        <label for="activity_time_periods">
                            Activity Time Period:
                            <span class="hint">?
                                <span class="hint-text">Select the time periods when the activity takes place.
                            </span>
                        </label>
                        <div class="form-checkbox-group">
                            <?php
                            // Time periods
                            $time_periods = [
                                "Morning", 
                                "Afternoon", 
                                "Evening"
                            ];

                            // Create vertical list with checkboxes on the left
                            foreach ($time_periods as $time_period) {
                                $checked = in_array($time_period, $activity_time_periods) ? "checked" : "";
                                echo "<div class='form-checkbox-item'>";
                                echo "<input type='checkbox' name='activity_time_periods[]' value='$time_period' $checked>";
                                echo "<label style='margin: 0;'>$time_period</label>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <span id="error_message"><?php echo isset($activity) ? $activity->activity_time_periods_error_mes : ''; ?></span>
                    </div>

                    <!-- Activity Domain Section -->
                    <div class="form-field form-field-top">
                        <label>
                            Activity Domains:
                            <span class="hint">?
                                <span class="hint-text">Select the domains this activity is related to.
                            </span>
                        </label>

                        <div class="form-checkbox-group">
                            <?php
                            // Activity domains
                            $domain_types = [
                                "Organization of community events", 
                                "Library support", 
                                "Help in the community store", 
                                "Support in the community grocery store", 
                                "Cleaning and maintenance of public spaces", 
                                "Participation in urban gardening projects"
                            ];

                            // Create list with checkboxes and text
                            foreach ($domain_types as $domain) {
                                $checked = in_array($domain, $activity_domains) ? "checked" : "";
                                echo "<div class='form-checkbox-item'>";
                                echo "<input type='checkbox' name='activity_domains[]' value='$domain' $checked>";
                                echo "<label style='margin: 0;'>$domain</label>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <span id="error_message"><?php echo isset($activity) ? $activity->activity_domains_error_mes : ''; ?></span>
                    </div>

                    <!-- Entry Clerk Text Input -->
                    <div class="form-field">
                        <label for="entry_clerk">
                            Entry Clerk:
                            <span class="hint">?
                                <span class="hint-text">Enter the name of the person filling out this form (ex: "Jane Smith").
                            </span>
                        </label>
                        <input name="entry_clerk" type="text" id="text_input" value="<?php echo $entry_clerk ?>">
                        <span id="error_message"><?php echo isset($activity) ? $activity->entry_clerk_error_mes : ''; ?></span>
                    </div>

                    <!-- Additional Notes Text Input -->
                    <div class="form-field form-field-top">
                        <label for="additional_notes">
                            Additional Notes:
                            <span class="hint">?
                                <span class="hint-text">Enter any additional notes or comments about the volunteer. This field is optional.
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