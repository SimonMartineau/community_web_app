<?php

    // Include classes
    include("classes/connect.php");
    include("classes/add_volunteer.php");

    // Variables to keep user input data if failed submit
    $first_name = "";
    $last_name = "";
    $gender = "";
    $date_of_birth = "";
    $address = "";
    $zip_code = "";
    $telephone_number = "";
    $email = "";
    $volunteer_availability = "";
    $volunteer_interests = "";
    $other_interest = "";
    $registration_supervisor = "";
    $assigned_area = "";
    $additional_notes = "";

    // Check if user has submitted info
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $volunteer = new Add_Volunteer();
        $result = $volunteer->evaluate($_POST);

        // If there are errors 
        if(!$result){
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
            if(isset($_POST['volunteer_availability'])){ // Due to uncertain entry
                $volunteer_availability = $_POST['volunteer_availability'];
            }
            if(isset($_POST['volunteer_interests'])){ // Due to uncertain entry
                $volunteer_interests = $_POST['volunteer_interests'];
            }
            $other_interest = $_POST['other_interest'];
            $registration_supervisor = $_POST['registration_supervisor'];
            $assigned_area = $_POST['assigned_area'];
            $additional_notes = $_POST['additional_notes'];
        } else{
            // Reset the user input variables.
            $first_name = "";
            $last_name = "";
            $gender = "";
            $date_of_birth = "";
            $address = "";
            $zip_code = "";
            $telephone_number = "";
            $email = "";
            $volunteer_availability = "";
            $volunteer_interests = "";
            $other_interest = "";
            $registration_supervisor = "";
            $assigned_area = "";
            $additional_notes = "";

            // Changing the page.
            header("Location: add_volunteer.php");
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
                    <span style="font-size: 24px; font-weight: bold;">Add Volunteer Form</span>
                </div>

                <!-- Input area -->
                <div id="input_section">

                    <!-- Form text input -->
                    <form method="post" action="add_volunteer.php">

                        <!-- First name text input -->
                        <div class="input-container">
                            <input name="first_name" type="text" id="text_input" placeholder="First name" value="<?php echo $first_name ?>">
                            <span id="error"><?php echo isset($volunteer) ? $volunteer->first_name_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Last name text input -->
                        <div class="input-container">
                            <input name="last_name" type="text" id="text_input" placeholder="Last name" value="<?php echo $last_name ?>">
                            <span id="error"><?php echo isset($volunteer) ? $volunteer->last_name_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Gender bubble check -->
                        <div class="input-container">
                            Gender:
                            <input type="radio" name="gender" value="male" <?php echo ($gender == 'male') ? 'checked' : ''; ?>> Male
                            <input type="radio" name="gender" value="female" <?php echo ($gender == 'female') ? 'checked' : ''; ?>> Female
                            <input type="radio" name="gender" value="other" <?php echo ($gender == 'other') ? 'checked' : ''; ?>> Other
                            <span id="error"><?php echo isset($volunteer) ? $volunteer->gender_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Date of birth input -->
                        <div class="input-container">
                            Date of Birth: <input type="date" name="date_of_birth" value="<?php echo $date_of_birth ?>">
                            <span id="error"><?php echo isset($volunteer) ? $volunteer->date_of_birth_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Address text input -->
                        <div class="input-container">
                            <input name="address" type="text" id="text_input" placeholder="Address" value="<?php echo $address ?>">
                            <span id="error"><?php echo isset($volunteer) ? $volunteer->address_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- ZIP code text input -->
                        <div class="input-container">
                            <input name="zip_code" type="text" id="text_input" placeholder="ZIP code" value="<?php echo $zip_code ?>">
                            <span id="error"><?php echo isset($volunteer) ? $volunteer->zip_code_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Telephone number text input -->
                        <div class="input-container">
                            <input name="telephone_number" type="text" id="text_input" placeholder="Telephone number" value="<?php echo $telephone_number ?>">
                            <span id="error"><?php echo isset($volunteer) ? $volunteer->telephone_number_error_mes : ''; ?></span>
                        </div>
                        <br><br>
                        
                        <!-- Email text input -->
                        <div class="input-container">
                            <input name="email" type="text" id="text_input" placeholder="Email" value="<?php echo $email ?>">
                            <span id="error"><?php echo isset($volunteer) ? $volunteer->email_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Volunteer availability text input -->
                        <div class="input-container">
                            <h4 style="text-align: center;">Weekly Availability</h4>
                            <span id="error"><?php echo isset($volunteer) ? $volunteer->volunteer_availability_error_mes : ''; ?></span>
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
                                
                                $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
                                $time_periods = ["Morning", "Afternoon", "Evening"];
                                foreach ($days as $day) {
                                    echo "<tr>";
                                    echo "<td>$day</td>";
                                    foreach ($time_periods as $time_period){
                                        $available_moment = "{$day}-{$time_period}";
                                        echo "<td><input type='checkbox' name='volunteer_availability[]'></td>";
                                        
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </table>
                        </div>
                        <br>

                        <!-- Volunteer's Interests Table -->
                        <div class="input-container">
                            <h4 style="text-align: center;">Volunteer's Interests</h4> 
                            <span id="error"><?php echo isset($volunteer) ? $volunteer->volunteer_interests_error_mes : ''; ?></span>
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
                                    echo "<td><input type='checkbox' name='volunteer_interests[]'></td>";
                                    echo "</tr>";
                                }
                                ?>
                            </table>
                        </div>
                        <br>

                        <!-- "Others" text input -->
                        <div class="input-container">
                            <input name="other_interest" type="text" id="text_input" placeholder="Other Interest" value="<?php echo $other_interest ?>">
                        </div>
                        <br><br>

                        <!-- Registration Supervisor text input -->
                        <div class="input-container">
                            <input name="registration_supervisor" type="text" id="text_input" placeholder="Registration Supervisor" value="<?php echo $registration_supervisor ?>">
                            <span id="error"><?php echo isset($volunteer) ? $volunteer->registration_supervisor_error_mes : ''; ?></span>
                        </div>
                        <br><br>

                        <!-- Assigned area dropdown -->
                        <div class="input-container">
                            Assigned Area: 
                            <span id="error"><?php echo isset($volunteer) ? $volunteer->assigned_area_error_mes : ''; ?></span>
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