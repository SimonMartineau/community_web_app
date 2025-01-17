<?php

    // Include classes
    include("classes/connect.php");
    include("classes/add_social_activity.php");

    // Variables to keep user input data if failed submit
    $activity_name = "";
    $activity_duration = "";
    $activity_time_period = "";
    $activity_domains = "";
    $registration_supervisor = "";
    $assigned_area = "";
    $additional_notes = "";

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $activity = new Add_Social_Activity();
        $result = $activity->evaluate($_POST);

        // If there are errors 
        if(!$result){
            // Re-enter user input data in prompts
            $activity_name = $_POST['activity_name'];
            $activity_duration = $_POST['activity_duration'];
            if(isset($_POST['activity_time_period'])){ // Due to uncertain entry
                $activity_time_period = $_POST['activity_time_period'];
            }
            if(isset($_POST['activity_domains'])){ // Due to uncertain entry
                $activity_domains = $_POST['activity_domains'];
            }
            $registration_supervisor = $_POST['registration_supervisor'];
            $assigned_area = $_POST['assigned_area'];
            $additional_notes = $_POST['additional_notes'];
        } else{
            // Reset the user input variables.
            $activity_name = "";
            $activity_duration = "";
            $activity_time_period = "";
            $activity_domains = "";
            $registration_supervisor = "";
            $assigned_area = "";
            $additional_notes = "";

            // Changing the page.
            header("Location: add_social_activity.php");
            die; // Ending the script
        }    
    }
?> 


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Social Activity | Give and Receive</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <style></style>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("header.php"); ?>

        <!-- Middle area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            
            <!-- Major rectangle area -->
            <div id="major_rectangle">

                <!-- Title -->
                <div id="section_title" style="margin-bottom: 20px;">
                    <span style="font-size: 24px; font-weight: bold;">Add Social Activity Form</span>
                </div>

                <!-- Form area -->
                <div id="form_section">

                    <!-- Form text input -->
                    <form method="post" action="add_social_activity.php">

                        <!-- Activity name text input -->
                        <div class="input_container">
                            <input name="activity_name" type="text" id="text_input" placeholder="Activity name" value="<?php echo $activity_name ?>">
                            <span id="error_message"><?php echo isset($activity) ? $activity->activity_name_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Activity duration text input -->
                        <div class="input_container">
                            <input name="activity_duration" type="text" id="text_input" placeholder="Activity duration" value="<?php echo $activity_duration ?>">
                            <span id="error_message"><?php echo isset($activity) ? $activity->activity_duration_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Activity time period bubble check -->
                        <div class="input_container">
                            Activity time period:
                            <input type="radio" name="activity_time_period" value="morning" <?php echo ($activity_time_period == 'morning') ? 'checked' : ''; ?>> Morning
                            <input type="radio" name="activity_time_period" value="afternoon" <?php echo ($activity_time_period == 'afternoon') ? 'checked' : ''; ?>> Afternoon
                            <input type="radio" name="activity_time_period" value="evening" <?php echo ($activity_time_period == 'evening') ? 'checked' : ''; ?>> Evening
                            <span id="error_message"><?php echo isset($activity) ? $activity->activity_time_period_error_mes : ''; ?></span>
                        </div>
                        <br><br>

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
                                    echo "<td><input type='checkbox' name='activity_domains[]'></td>";
                                    echo "</tr>";
                                }
                                ?>
                            </table>
                        </div>
                        <br>

                        <!-- Registration Supervisor text input -->
                        <div class="input_container">
                            <input name="registration_supervisor" type="text" id="text_input" placeholder="Registration Supervisor" value="<?php echo $registration_supervisor ?>">
                            <span id="error_message"><?php echo isset($activity) ? $activity->registration_supervisor_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Assigned area dropdown -->
                        <div class="input_container">
                            Assigned Area: 
                            <span id="error_message"><?php echo isset($activity) ? $activity->assigned_area_error_mes : ''; ?></span>
                            <select name="assigned_area" value="<?php echo $assigned_area ?>">
                                <option value="">Select an area</option>
                                <option value="Area 1">Area 1</option>
                                <option value="Area 2">Area 2</option>
                                <option value="Area 3">Area 3</option>
                                <option value="Area 4">Area 4</option>
                            </select> 
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