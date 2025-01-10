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
            width:800px; 
            margin: auto; 
            padding: 10px;
            padding-top: 50px;
            text-align: center;
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
                    <form method="post" action="signup.php">

                        <!-- First name text input -->
                        <input name="first_name" type="text" id="text_input" placeholder="First name"><br><br>

                        <!-- Last name text input -->
                        <input name="last_name" type="text" id="text_input" placeholder="Last name"><br><br>

                        <!-- Gender bubble check -->
                        Gender:
                        <input type="radio" name="gender" value="female"> Female
                        <input type="radio" name="gender" value="male"> Male
                        <input type="radio" name="gender" value="other"> Other
                        <br><br>

                        <!-- Date of birth input -->
                        Date of Birth: <input type="date" name="date_of_birth"><br><br>

                        <!-- Address text input -->
                        <input name="address" type="text" id="text_input" placeholder="Address"><br><br>

                        <!-- ZIP code text input -->
                        <input name="zip_code" type="text" id="text_input" placeholder="ZIP code"><br><br>

                        <!-- Telephone number text input -->
                        <input name="telephone_number" type="text" id="text_input" placeholder="Telephone number"><br><br>
                        
                        <!-- Email text input -->
                        <input name="email" type="text" id="text_input" placeholder="Email"><br><br>

                        <!-- Weekly availability text input -->
                        <h4 style="display: inline;">Weekly Availability</h4>
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
                        <br>

                        <!-- Volunteer's Interests Table -->
                        <h4 style="display: inline;">Volunteer's Interests</h4> 
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
                        <br>

                        <!-- "Others" text input -->
                        <input name="other_interest" type="text" id="text_input" placeholder="Other Interest"><br><br>

                        <!-- Registration Supervisor text input -->
                        <input name="registration_supervisor" type="text" id="text_input" placeholder="Registration Supervisor"><br><br>

                        <!-- Assigned area dropdown -->
                        Assigned Area: 
                        <select name="assigned_area">
                            <option value="">Select an area</option>
                            <option value="Area 1">Area 1</option>
                            <option value="Area 2">Area 2</option>
                            <option value="Area 3">Area 3</option>
                            <option value="Area 4">Area 4</option>
                        </select>    
                        <br><br>

                        <!-- Additional notes text input -->
                        Additional Notes:
                        <br>
                        <textarea name="notes" rows="10" cols="60" id="additional_notes"></textarea>
                        <br><br>

                        <!-- Submit button -->
                        <input type="submit" id="submit_button" value="Submit">
                        <br><br>
                    </form>
                </div>

                
            </div>
        </div>
            
        
    </body>
</html>