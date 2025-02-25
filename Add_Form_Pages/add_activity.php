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
            header("Location: ../Listing_Pages/all_activities.php");
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

        <!-- Header bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Middle area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            
            <!-- Major rectangle area -->
            <div id="major_rectangle">

                <!-- Title -->
                <div id="section_title" style="margin-bottom: 20px;">
                    <span style="font-size: 24px; font-weight: bold;">Add Activity Form</span>
                </div>

                <!-- Error message -->
                <div style="text-align: center;">
                    <span id="main_error" style="color: red; font-weight: bold;">
                        <?php echo isset($submit_success) ? "Missing information. Could not send. Please try again." : ""; ?>
                    </span>
                </div>

                <!-- Form area -->
                <div id="form_section">

                    <!-- Form text input -->
                    <form method="post" action="../Add_Form_Pages/add_activity.php">

                        <!-- Activity name text input -->
                        <div class="input_container">
                            Activity name:
                            <input name="activity_name" type="text" id="text_input" value="<?php echo $activity_name ?>">
                            <span id="error_message"><?php echo isset($activity) ? $activity->activity_name_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Activity number of places text input -->
                        <div class="input_container">
                            Number of places:
                            <input name="number_of_places" type="text" id="text_input" value="<?php echo $number_of_places ?>">
                            <span id="error_message"><?php echo isset($activity) ? $activity->number_of_places_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Activity duration text input -->
                        <div class="input_container">
                            Activity duration:
                            <input name="activity_duration" type="text" id="text_input" value="<?php echo $activity_duration ?>">
                            <span id="error_message"><?php echo isset($activity) ? $activity->activity_duration_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Activity location text input -->
                        <div class="input_container">
                            Activity location:
                            <input name="activity_location" type="text" id="text_input" placeholder="(Optional)" value="<?php echo $activity_location ?>">
                        </div>
                        <br><br>
                        
                        <!-- Dates input -->
                        <div class="input_container">
                            <!-- Include Flatpickr CSS & JS -->
                            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

                            <!-- Multi-date picker input -->
                            Activity Dates: <input type="text" id="activity_dates" name="activity_dates" value="<?php echo $activity_dates ?>">
                            <span id="error_message"><?php echo isset($activity) ? $activity->activity_dates_error_mes : ''; ?></span>

                            <!-- Flatpickr JS -->
                            <script>
                            flatpickr("#activity_dates", {
                                mode: "multiple", // Enables multiple date selection
                                dateFormat: "Y-m-d", // Format of the selected dates
                            });
                            </script>
                        </div>


                        <!-- Activity time period table -->
                        <div class="input_container">
                            <h4 style="text-align: center;">Activity Time Period</h4> 
                            <span id="error_message"><?php echo isset($activity) ? $activity->activity_time_periods_error_mes : ''; ?></span>
                        </div>
                        <div style="text-align: center;">
                            <table border="1" style="border-collapse: collapse; text-align: center; width: 50%; margin-left: auto; margin-right: auto;">
                                <tr>
                                    <th>Time Period</th>
                                    <th>Contract</th>
                                </tr>
                                <?php

                                // Time periods
                                $time_periods = [
                                    "Morning", 
                                    "Afternoon", 
                                    "Evening"
                                ];

                                // Create table with checkboxes
                                foreach ($time_periods as $time_period) {
                                    echo "<tr>";
                                    echo "<td>$time_period</td>";
                                    if (in_array($time_period, $activity_time_periods)){
                                        echo "<td><input type='checkbox' name='activity_time_periods[]' value='$time_period' checked></td>";
                                    } else {
                                        echo "<td><input type='checkbox' name='activity_time_periods[]' value='$time_period'></td>";
                                    } 
                                    echo "</tr>";
                                }
                                ?>
                            </table>
                        </div>
                        <br>

                        <!-- Activity domains table -->
                        <div class="input_container">
                            <h4 style="text-align: center;">Activity Domains</h4> 
                            <span id="error_message"><?php echo isset($activity) ? $activity->activity_domains_error_mes : ''; ?></span>
                        </div>
                        <div style="text-align: center;">
                            <table border="1" style="border-collapse: collapse; text-align: center; width: 50%; margin-left: auto; margin-right: auto;">
                                <tr>
                                    <th>Activity</th>
                                    <th>Contract</th>
                                </tr>
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

                                // Create table with checkboxes
                                foreach ($domain_types as $domain) {
                                    echo "<tr>";
                                    echo "<td>$domain</td>";
                                    if (in_array($domain, $activity_domains)){
                                        echo "<td><input type='checkbox' name='activity_domains[]' value='$domain' checked></td>";
                                    } else {
                                        echo "<td><input type='checkbox' name='activity_domains[]' value='$domain'></td>";
                                    }  
                                    echo "</tr>";
                                }
                                ?>
                            </table>
                        </div>
                        <br>

                        <!-- Entry Clerk text input -->
                        <div class="input_container">
                            Entry Clerk:
                            <input name="entry_clerk" type="text" id="text_input" value="<?php echo $entry_clerk ?>">
                            <span id="error_message"><?php echo isset($activity) ? $activity->entry_clerk_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Additional notes text input -->
                        <div style="text-align: center">
                            Additional Notes:
                            <br>
                            <textarea name="additional_notes" rows="10" cols="60" id="additional_notes" placeholder="(Optional)" value="<?php echo $additional_notes ?>"></textarea>
                        </div>
                        <br><br>

                        <!-- Submit button -->
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