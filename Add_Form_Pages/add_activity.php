<!-- PHP Code -->
<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/add_activity.php");

    // Variables to keep user input data if failed submit
    $activity_name = "";
    $activity_duration = "";
    $activity_location = "";
    $number_of_places = "";
    $activity_dates = "";
    $activity_time_periods = [];
    $activity_domains = [];
    $entry_clerk = "";
    $additional_notes = "";

    
    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Create a Add_Activity object for form evaluation
        $activity = new Add_Activity();
        $submit_success = $activity->evaluate($_POST); // Evaluate the form

        // If there are errors ...
        if(!$submit_success){
            // Re-enter user input data in prompts
            $activity_name = $_POST['activity_name'];
            $activity_duration = $_POST['activity_duration'];
            $activity_location = $_POST['activity_location'];
            $number_of_places = $_POST['number_of_places'];
            $activity_dates = $_POST['activity_dates'];
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
            header("Location: ../Profile_Pages/activity_profile.php?activity_id=" . $activity->activity_id);;
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
        <title>Add Activity | Give and Receive</title>
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
                    <span style="font-size: 24px; font-weight: bold;">Add Activity Form</span>
                </div>

                <!-- Error Message -->
                <div style="text-align: center;">
                    <span id="main_error" style="color: red; font-weight: bold;">
                        <?php echo isset($submit_success) ? "Missing information. Could not send. Please try again." : ""; ?>
                    </span>
                </div>

                <!-- Form Area -->
                <form action="../Add_Form_Pages/add_activity.php" method="post" class="form-layout" form>

                    <!-- Activity Name Text Input -->
                    <div class="form-field">
                        <label for="username">
                            Activity Name:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter a short, descriptive name for the activity (e.g., "Morning Run").
                            </span>
                        </span>
                        </label>
                        <input name="activity_name" type="text" id="text_input" value="<?php echo $activity_name ?>">
                        <span id="error_message"><?php echo isset($activity) ? $activity->activity_name_error_mes : ''; ?></span>
                    </div>

                    <!-- Activity Number of Places Text Input -->
                    <div class="form-field">
                        <label for="email">
                            Number of Places:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter a short, descriptive name for the activity (e.g., "Morning Run").
                            </span>
                        </span>
                        </label>
                        <input name="number_of_places" type="text" id="text_input" value="<?php echo $number_of_places ?>">
                        <span id="error_message"><?php echo isset($activity) ? $activity->number_of_places_error_mes : ''; ?></span>
                    </div>

                    <!-- Activity Duration Text Input -->
                    <div class="form-field">
                        <label for="password">
                            Activity Duration:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter a short, descriptive name for the activity (e.g., "Morning Run").
                            </span>
                        </label>
                        <input name="activity_duration" type="text" id="text_input" value="<?php echo $activity_duration ?>">
                        <span id="error_message"><?php echo isset($activity) ? $activity->activity_duration_error_mes : ''; ?></span>
                    </div>

                    <!-- Activity Location Text Input -->
                    <div class="form-field">
                        <label for="password">
                            Activity Location:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter a short, descriptive name for the activity (e.g., "Morning Run").
                            </span>
                        </label>
                        <input name="activity_location" type="text" id="text_input" placeholder="(Optional)" value="<?php echo $activity_location ?>">
                    </div>

                    <!-- Dates Input -->
                    <div class="form-field">
                        <label for="password">
                            Activity Dates:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter a short, descriptive name for the activity (e.g., "Morning Run").
                            </span>
                        </label>
                        <!-- Include Flatpickr CSS & JS -->
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                        <!-- Multi-date picker Input -->
                        <input type="text" id="activity_dates" name="activity_dates" value="<?php echo $activity_dates ?>">
                        <span id="error_message"><?php echo isset($activity) ? $activity->activity_dates_error_mes : ''; ?></span>

                        <!-- Flatpickr JS -->
                        <script>
                        flatpickr("#activity_dates", {
                            mode: "multiple", // Enables multiple date selection
                            dateFormat: "Y-m-d", // Format of the selected dates
                        });
                        </script>
                    </div>

                    <!-- Activity Time Period Table -->
                    <div class="form-field form-field-top">
                        <label for="password">
                            Activity Time Period:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter a short, descriptive name for the activity (e.g., "Morning Run").
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
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter a short, descriptive name for the activity (e.g., "Morning Run").
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
                        <label for="password">
                            Entry Clerk:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter a short, descriptive name for the activity (e.g., "Morning Run").
                            </span>
                        </label>
                        <input name="entry_clerk" type="text" id="text_input" value="<?php echo $entry_clerk ?>">
                        <span id="error_message"><?php echo isset($activity) ? $activity->entry_clerk_error_mes : ''; ?></span>
                    </div>

                    <!-- Additional Notes Text Input -->
                    <div class="form-field form-field-top">
                        <label for="password">
                            Additional Notes:
                            <span class="tooltip">?
                                <span class="tooltip-text">Enter a short, descriptive name for the activity (e.g., "Morning Run").
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