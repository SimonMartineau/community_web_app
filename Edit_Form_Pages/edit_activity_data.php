<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/edit_activity_data.php");
    include("../Classes/functions.php");


    if (isset($_GET['activity_id'])) {
        $activity_id = $_GET['activity_id'];

        // Collecting activity data (only 1 row needed)
        $activity_data_row = fetch_data(
            "SELECT * FROM Activities
                    WHERE id = '$activity_id'"
        )[0];

        // Collecting activity time periods data
        $activity_time_periods_data = fetch_data(
            query: "SELECT * FROM Activity_Time_Periods
                    WHERE activity_id = '$activity_id'"
        );

        // Collecting activity domains data
        $activity_domain_data = fetch_data(
            "SELECT * FROM Activity_Domains
                    WHERE activity_id = '$activity_id'"
        );

    }

    // Variables to keep user input data if failed submit
    $activity_name = $activity_data_row['activity_name'];
    $activity_duration = $activity_data_row['activity_duration'];
    $activity_location = $activity_data_row['activity_location'];
    $number_of_places = $activity_data_row['number_of_places'];
    $activity_date = $activity_data_row['activity_date'];
    // For activity_time_periods_data, we extract the time_period column and insert the data in $activity_time_periods[].
    foreach($activity_time_periods_data as $activity_time_periods_data_row){
        $activity_time_periods[] = $activity_time_periods_data_row['time_period'];
    }
    // For activity_domain_data, we extract the domain column and insert the data in $activity_domains[].
    foreach($activity_domain_data as $activity_domain_data_row){
        $activity_domains[] = $activity_domain_data_row['domain'];
    }
    $entry_clerk = $activity_data_row['entry_clerk'];
    $additional_notes = $activity_data_row['additional_notes'];
    $registration_date = $activity_data_row['registration_date'];

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $activity = new Edit_Activity();
        $submit_success = $activity->evaluate($activity_id, $_POST);

        // If there are errors 
        if(!$submit_success){
            // Re-enter user input data in prompts
            $activity_name = $_POST['activity_name'];
            $activity_duration = $_POST['activity_duration'];
            $activity_location = $_POST['activity_location'];
            $number_of_places = $_POST['number_of_places'];
            $activity_date = $_POST['activity_date'];
            if(isset($_POST['activity_time_periods'])){ // Due to uncertain entry
                $activity_time_periods = $_POST['activity_time_periods'];
            }
            if(isset($_POST['activity_domains'])){ // Due to uncertain entry
                $activity_domains = $_POST['activity_domains'];
            }
            $entry_clerk = $_POST['entry_clerk'];
            $additional_notes = $_POST['additional_notes'];
            $registration_date = $_POST['registration_date'];

        } else{
            // Changing the page.
            header("Location: ../Profile_Pages/activity_profile.php?activity_id=" . $activity_id);
            die; // Ending the script
        }    
    }
?> 


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Activity | Give and Receive</title>
        <link rel="stylesheet" href="../style.css">
    </head>

    <style></style>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Middle area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            
            <!-- Major rectangle area -->
            <div id="major_rectangle">

                <!-- Title -->
                <div id="section_title" style="margin-bottom: 20px;">
                    <span style="font-size: 24px; font-weight: bold;">Edit Activity Form</span>
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
                    <form method="post" action="../Edit_Form_Pages/edit_activity_data.php?activity_id=<?php echo $activity_id; ?>">

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

                        <!-- Date input -->
                        <div class="input_container">
                            Activity Date: 
                            <input type="date" name="activity_date" value="<?php echo $activity_date ?>">
                            <span id="error_message"><?php echo isset($activity) ? $activity->activity_date_error_mes : ''; ?></span>
                        </div>
                        <br><br>

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
                                $time_periods = [
                                    "Morning", 
                                    "Afternoon", 
                                    "Evening"
                                ];
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
                                $domain_types = [
                                    "Organization of community events", 
                                    "Library support", 
                                    "Help in the community store", 
                                    "Support in the community grocery store", 
                                    "Cleaning and maintenance of public spaces", 
                                    "Participation in urban gardening projects"
                                ];
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
                            Entry clerk:
                            <input name="entry_clerk" type="text" id="text_input" value="<?php echo $entry_clerk ?>">
                            <span id="error_message"><?php echo isset($activity) ? $activity->entry_clerk_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Additional notes text input -->
                        <div style="text-align: center">
                            Additional Notes:
                            <br>
                            <textarea name="additional_notes" rows="10" cols="60" id="additional_notes" placeholder="(Optional)"><?php echo $additional_notes ?></textarea>
                        </div>
                        <br><br>

                        <!-- Registration date text input -->
                        <div class="input_container">
                            Registration date:
                            <input name="registration_date" type="date" id="text_input" value="<?php echo $registration_date ?>">
                            <span id="error_message"><?php echo isset($activity) ? $activity->registration_date_error_mes : ''; ?></span>
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