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
    </head>

    <style>
        #major_rectangle{
            margin-top: 20px;
            background-color: white;
            padding: 10px;
            min-height: 400px; 
            flex:1.5; 
            padding: 20px;
            padding-left: 20px; 
            padding-right: 0px;
            background-color: #f9f9f9; 
            border-radius: 8px;
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

        #input_section{
            background-color: white; 
            width:1000px; 
            margin: auto; 
            padding: 10px;
            padding-top: 50px;
        }

        #text_input{
            height: 40px;
            width: 300px;
            border-radius: 4px;
            border: solid 1px #ccc;
            padding: 4px;
            font-size: 14px;
        }

        #error {
            color: red;
            font-weight: bold; /* Optional: Make it bold for emphasis */
        }

        #additional_notes{
            width: 80%;
            height: 150px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #submit_button{
            width: 300px;
            height: 40px;
            border-radius: 8px;
            border: none;
            background-color: rgb(59,89,152);
            color: white;
            font-weight: bold;
        }

        .input-container {
            display: flex;
            justify-content: center; /* Centers the input field */
            align-items: center; /* Aligns vertically (in case the input field has a different height from the error message) */
            position: relative;
        }

        #text_input {
            margin-right: 10px; /* Adds space between input and error message */
        }

        #error {
            position: absolute;
            left: 70%; /* Places the error message to the right of the input */
            margin-left: 10px; /* Adds space between the input and error message */
            color: red;
            font-weight: bold; /* Optional: Make it bold for emphasis */

        }

        
    </style>

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

                <!-- Input area -->
                <div id="input_section">

                    <!-- Form text input -->
                    <form method="post" action="add_social_activity.php">

                        <!-- Activity name text input -->
                        <div class="input-container">
                            <input name="activity_name" type="text" id="text_input" placeholder="Activity name" value="<?php echo $activity_name ?>">
                            <span id="error"><?php echo isset($activity) ? $activity->activity_name_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Activity duration text input -->
                        <div class="input-container">
                            <input name="activity_duration" type="text" id="text_input" placeholder="Activity duration" value="<?php echo $activity_duration ?>">
                            <span id="error"><?php echo isset($activity) ? $activity->activity_duration_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Activity time period bubble check -->
                        <div class="input-container">
                            Activity time period:
                            <input type="radio" name="activity_time_period" value="morning" <?php echo ($activity_time_period == 'morning') ? 'checked' : ''; ?>> Morning
                            <input type="radio" name="activity_time_period" value="afternoon" <?php echo ($activity_time_period == 'afternoon') ? 'checked' : ''; ?>> Afternoon
                            <input type="radio" name="activity_time_period" value="evening" <?php echo ($activity_time_period == 'evening') ? 'checked' : ''; ?>> Evening
                            <span id="error"><?php echo isset($activity) ? $activity->activity_time_period_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Activity domains table -->
                        <div class="input-container">
                            <h4 style="text-align: center;">Activity Domains</h4> 
                            <span id="error"><?php echo isset($activity) ? $activity->activity_domains_error_mes : ''; ?></span>
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
                        <div class="input-container">
                            <input name="registration_supervisor" type="text" id="text_input" placeholder="Registration Supervisor" value="<?php echo $registration_supervisor ?>">
                            <span id="error"><?php echo isset($activity) ? $activity->registration_supervisor_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Assigned area dropdown -->
                        <div class="input-container">
                            Assigned Area: 
                            <span id="error"><?php echo isset($activity) ? $activity->assigned_area_error_mes : ''; ?></span>
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
                        <div class="input-container">
                            <input type="submit" id="submit_button" value="Submit">
                        </div>
                        <br><br>
                        
                    </form>
                </div>

                
            </div>
        </div>
            
        
    </body>
</html>