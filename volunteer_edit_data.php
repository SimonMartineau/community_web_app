<?php

    // Include classes
    include("classes/connect.php");
    include("classes/volunteer_edit_data.php");
    include("classes/volunteer_functions.php");

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $member_data = fetch_member_data($id)[0];
        $interests_data = fetch_member_interest_data($id);
        $availability_data = fetch_member_availability_data($id);
    }

    // Default entry values on page startup.
    $first_name = $member_data['first_name'];
    $last_name = $member_data['last_name'];
    $gender = $member_data['gender'];
    $date_of_birth = $member_data['date_of_birth'];
    $address = $member_data['address'];
    $zip_code = $member_data['zip_code'];
    $telephone_number = $member_data['telephone_number'];
    $email = $member_data['email'];
    $points = $member_data['points'];
    $hours_completed = $member_data['hours_completed'];
    // For interest data, we extract the interest column and insert the data in $volunteer_interests[].
    foreach($interests_data as $interests_data_row){
        $volunteer_interests[] = $interests_data_row['interest'];
    }
    $volunteer_availability = $availability_data;
    // For availability data, we extract the weekday and timeperiod columns and insert the data in $volunteer_availability[].
    foreach($availability_data as $availability_data_row){
        $weekday = $availability_data_row['weekday'];
        $time_period = $availability_data_row['time_period'];
        $available_moment = "{$weekday}-{$time_period}";
        $volunteer_availability[] = $available_moment;
    }
    $organizer_name = $member_data['organizer_name'];
    $assigned_area = $member_data['assigned_area'];
    $additional_notes = $member_data['additional_notes'];
    $registration_date = $member_data['registration_date'];

    
    // Check if user has submitted info, we update entries.
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $volunteer = new Edit_Volunteer();
        $submit_success = $volunteer->evaluate($id, $_POST);

        // If there are errors 
        if(!$submit_success){
            // Re-enter user input data in prompts
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            if(isset($_POST['gender'])){ // Due to uncertain entry
                $gender = $_POST['gender'];
            }
            $date_of_birth = $_POST['date_of_birth'];
            $address = $_POST['address'];
            $zip_code = $_POST['zip_code'];
            $telephone_number = $_POST['telephone_number'];
            $email = $_POST['email'];
            $points = $_POST['points'];
            $hours_completed = $_POST['hours_completed'];
            if(isset($_POST['volunteer_availability'])){ // Due to uncertain entry
                $volunteer_availability = $_POST['volunteer_availability'];
            }
            if(isset($_POST['volunteer_interests'])){ // Due to uncertain entry
                $volunteer_interests = $_POST['volunteer_interests'];
            }
            $organizer_name = $_POST['organizer_name'];
            $assigned_area = $_POST['assigned_area'];
            $additional_notes = $_POST['additional_notes'];
            $registration_date = $_POST['registration_date'];


        } else{ // If there are no errors in the submission.
            // Changing the page.
            header("Location: volunteer_profile.php?id=" . $id);
            die; // Ending the script
        }    
    }
?> 


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Volunteer Data | Give and Receive</title>
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

        .input_container {
            display: flex;
            justify-content: center; /* Centers the input field */
            align-items: center; /* Aligns vertically (in case the input field has a different height from the error message) */
            position: relative;
        }

        #text_input {
            margin-right: 10px; /* Adds space between input and error message */
        }

        #error_message{
            position: absolute;
            left: 70%; /* Places the error message to the right of the input */
            margin-left: 10px; /* Adds space between the input and error message */
            color: red;
            font-weight: bold; /* Optional: Make it bold for emphasis */
        }

        #main_error {
            text-align: center;
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
                    <span style="font-size: 24px; font-weight: bold;">Edit Volunteer Data</span>
                </div>

                <!-- Error message -->
                <div style="text-align: center;">
                    <span id="main_error" style="color: red; font-weight: bold;">
                        <?php echo isset($submit_success) ? "Missing information. Could not send. Please try again." : ""; ?>
                    </span>
                </div>

                <!-- Input area -->
                <div id="input_section">

                    <!-- Form text input -->
                    <form method="post" action="volunteer_edit_data.php?id=<?php echo $id; ?>">

                        <!-- First name text input -->
                        <div class="input_container">
                            First name:
                            <input name="first_name" type="text" id="text_input" placeholder="First name" value="<?php echo $first_name ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->first_name_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Last name text input -->
                        <div class="input_container">
                            Last name:
                            <input name="last_name" type="text" id="text_input" placeholder="Last name" value="<?php echo $last_name ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->last_name_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Gender bubble check -->
                        <div class="input_container">
                            Gender:
                            <input type="radio" name="gender" value="Male" <?php echo ($gender == 'Male') ? 'checked' : ''; ?>> Male
                            <input type="radio" name="gender" value="Female" <?php echo ($gender == 'Female') ? 'checked' : ''; ?>> Female
                            <input type="radio" name="gender" value="Other" <?php echo ($gender == 'Other') ? 'checked' : ''; ?>> Other
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->gender_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Date of birth input -->
                        <div class="input_container">
                            Date of birth: <input type="date" name="date_of_birth" value="<?php echo $date_of_birth ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->date_of_birth_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Address text input -->
                        <div class="input_container">
                            Address:
                            <input name="address" type="text" id="text_input" placeholder="Address" value="<?php echo $address ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->address_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- ZIP code text input -->
                        <div class="input_container">
                            ZIP code:
                            <input name="zip_code" type="text" id="text_input" placeholder="ZIP code" value="<?php echo $zip_code ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->zip_code_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Telephone number text input -->
                        <div class="input_container">
                            Telephone number:
                            <input name="telephone_number" type="text" id="text_input" placeholder="Telephone number" value="<?php echo $telephone_number ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->telephone_number_error_mes : ''; ?></span>
                        </div>
                        <br><br>
                        
                        <!-- Email text input -->
                        <div class="input_container">
                            Email:
                            <input name="email" type="text" id="text_input" placeholder="Email" value="<?php echo $email ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->email_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Points text input -->
                        <div class="input_container">
                            Points:
                            <input name="points" type="text" id="text_input" placeholder="Points" value="<?php echo $points ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->points_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Hours completed text input -->
                        <div class="input_container">
                            Hours completed:
                            <input name="hours_completed" type="text" id="text_input" placeholder="Hours Completed" value="<?php echo $hours_completed ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->hours_completed_error_mes : ''; ?></span>
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
                                    <th>Check</th>
                                </tr>
                                <?php
                                $activities = [
                                    "Organization of community events", 
                                    "Library support", 
                                    "Help in the community store", 
                                    "Support in the community grocery store", 
                                    "Cleaning and maintenance of public spaces", 
                                    "Participation in urban gardening projects"
                                ];
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

                        <!-- Volunteer availability text input -->
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
                                
                                $week = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
                                $time_periods = ["Morning", "Afternoon", "Evening"];
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

                        <!-- Organizer Name text input -->
                        <div class="input_container">
                            Organizer name:
                            <input name="organizer_name" type="text" id="text_input" placeholder="Organizer Name" value="<?php echo $organizer_name ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->organizer_name_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                       <!-- Assigned area dropdown -->
                        <div class="input_container">
                            Assigned Area: 
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->assigned_area_error_mes : ''; ?></span>
                            <select name="assigned_area">
                                <option value="" <?php echo ($assigned_area == '') ? 'selected' : ''; ?>>Select an area</option>
                                <option value="Area 1" <?php echo ($assigned_area == 'Area 1') ? 'selected' : ''; ?>>Area 1</option>
                                <option value="Area 2" <?php echo ($assigned_area == 'Area 2') ? 'selected' : ''; ?>>Area 2</option>
                                <option value="Area 3" <?php echo ($assigned_area == 'Area 3') ? 'selected' : ''; ?>>Area 3</option>
                                <option value="Area 4" <?php echo ($assigned_area == 'Area 4') ? 'selected' : ''; ?>>Area 4</option>
                            </select> 
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
                            <input name="registration_date" type="date" id="text_input" placeholder="Email" value="<?php echo $registration_date ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->registration_date_error_mes : ''; ?></span>
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