<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/add_activity.php");

    // Variables to keep user input data if failed submit
    $activity_name = "";
    $activity_duration = "";
    $number_of_participants = "";
    $activity_date = "";
    $activity_time_periods = [];
    $activity_domains = [];
    $organizer_name = "";
    $additional_notes = "";

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $activity = new Add_Activity();
        $result = $activity->evaluate($_POST);

        // If there are errors 
        if(!$result){
            // Re-enter user input data in prompts
            $activity_name = $_POST['activity_name'];
            $activity_duration = $_POST['activity_duration'];
            $number_of_participants = $_POST['number_of_participants'];
            $activity_date = $_POST['activity_date'];
            if(isset($_POST['activity_time_periods'])){ // Due to uncertain entry
                $activity_time_periods = $_POST['activity_time_periods'];
            }
            if(isset($_POST['activity_domains'])){ // Due to uncertain entry
                $activity_domains = $_POST['activity_domains'];
            }
            $organizer_name = $_POST['organizer_name'];
            $additional_notes = $_POST['additional_notes'];

        } else{
            // Changing the page.
            header("Location: ../Listing_Pages/all_activities.php");
            die; // Ending the script
        }    
    }
?> 


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Activity | Give and Receive</title>
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
                    <span style="font-size: 24px; font-weight: bold;">Add Activity Form</span>
                </div>

                <!-- Form area -->
                <div id="form_section">

                    <!-- Form text input -->
                    <form method="post" action="../Add_Form_Pages/add_activity.php">

                        <!-- Activity name text input -->
                        <div class="input_container">
                            <input name="activity_name" type="text" id="text_input" placeholder="Activity name" value="<?php echo $activity_name ?>">
                            <span id="error_message"><?php echo isset($activity) ? $activity->activity_name_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Activity number of participants text input -->
                        <div class="input_container">
                            <input name="number_of_participants" type="text" id="text_input" placeholder="Number of participants" value="<?php echo $number_of_participants ?>">
                            <span id="error_message"><?php echo isset($activity) ? $activity->number_of_participants_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Activity duration text input -->
                        <div class="input_container">
                            <input name="activity_duration" type="text" id="text_input" placeholder="Activity duration" value="<?php echo $activity_duration ?>">
                            <span id="error_message"><?php echo isset($activity) ? $activity->activity_duration_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Date input -->
                        <div class="input_container">
                            Activity Date: <input type="date" name="activity_date" value="<?php echo $activity_date ?>">
                            <span id="error_message"><?php echo isset($activity) ? $activity->activity_date_error_mes : ''; ?></span>
                        </div>
                        <br>

                        <!-- Activity time period table -->
                        <div class="input_container">
                            <h4 style="text-align: center;">Activity Time Period</h4> 
                            <span id="error_message"><?php echo isset($activity) ? $activity->activity_time_periods_error_mes : ''; ?></span>
                        </div>
                        <div style="text-align: center;">
                            <table border="1" style="border-collapse: collapse; text-align: center; width: 50%; margin-left: auto; margin-right: auto;">
                                <tr>
                                    <th>Time Period</th>
                                    <th>Check</th>
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
                                    <th>Check</th>
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

                        <!-- Organizer Name text input -->
                        <div class="input_container">
                            <input name="organizer_name" type="text" id="text_input" placeholder="Registration Supervisor" value="<?php echo $organizer_name ?>">
                            <span id="error_message"><?php echo isset($activity) ? $activity->organizer_name_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Additional notes text input -->
                        <div style="text-align: center">
                            Additional Notes:
                            <br>
                            <textarea name="additional_notes" rows="10" cols="60" id="additional_notes" value="<?php echo $additional_notes ?>"></textarea>
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