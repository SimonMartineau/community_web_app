<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/add_volunteer.php");

    // Default entry values on page startup.
    $first_name = "";
    $last_name = "";
    $gender = "";
    $date_of_birth = "";
    $address = "";
    $zip_code = "";
    $telephone_number = "";
    $email = "";
    $volunteer_interests = [];
    $volunteer_availability = [];
    $organizer_name = "";
    $assigned_area = "";
    $additional_notes = "";

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $volunteer = new Add_Volunteer();
        $submit_success = $volunteer->evaluate($_POST);

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
            if(isset($_POST['volunteer_interests'])){ // Due to uncertain entry
                $volunteer_interests = $_POST['volunteer_interests'];
            }
            if(isset($_POST['volunteer_availability'])){ // Due to uncertain entry
                $volunteer_availability = $_POST['volunteer_availability'];
            }
            $organizer_name = $_POST['organizer_name'];
            $assigned_area = $_POST['assigned_area'];
            $additional_notes = $_POST['additional_notes'];

        } else{
            // Changing the page.
            header("Location: ../Add_Form_Pages/add_volunteer.php");
            die; // Ending the script
        }    
    }
?> 


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Volunteer | Give and Receive</title>
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
                <div id="section_title">
                    <span style="font-size: 24px; font-weight: bold;">Add Volunteer Form</span>
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
                    <form method="post" action="../Add_Form_Pages/add_volunteer.php">

                        <!-- First name text input -->
                        <div class="input_container">
                            <input name="first_name" type="text" id="text_input" placeholder="First name" value="<?php echo $first_name ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->first_name_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Last name text input -->
                        <div class="input_container">
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
                            Date of Birth: <input type="date" name="date_of_birth" value="<?php echo $date_of_birth ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->date_of_birth_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Address text input -->
                        <div class="input_container">
                            <input name="address" type="text" id="text_input" placeholder="Address" value="<?php echo $address ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->address_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- ZIP code text input -->
                        <div class="input_container">
                            <input name="zip_code" type="text" id="text_input" placeholder="ZIP code" value="<?php echo $zip_code ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->zip_code_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Telephone number text input -->
                        <div class="input_container">
                            <input name="telephone_number" type="text" id="text_input" placeholder="Telephone number" value="<?php echo $telephone_number ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->telephone_number_error_mes : ''; ?></span>
                        </div>
                        <br><br>
                        
                        <!-- Email text input -->
                        <div class="input_container">
                            <input name="email" type="text" id="text_input" placeholder="Email" value="<?php echo $email ?>">
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->email_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Volunteer's Interests Table -->
                        <div class="input_container">
                            <h4 style="text-align: center;">Volunteer's Interests</h4> 
                            <span id="error_message"><?php echo isset($volunteer) ? $volunteer->volunteer_interests_error_mes : ''; ?></span>
                        </div>
                        <div style="text-align: center;">
                            <table border="1" style="border-collapse: collapse; text-align: center; width: 50%;   margin-left: auto; margin-right: auto;">
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