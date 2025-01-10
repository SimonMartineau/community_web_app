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
                    <span style="font-size: 24px; font-weight: bold;">Add Social Activity Form</span>
                </div>

                <!-- Input area -->
                <div id="input_section">

                    <!-- Form text input -->
                    <form method="post" action="signup.php">

                        <!-- Activity name text input -->
                        <input name="activity_name" type="text" id="text_input" placeholder="Activity name"><br><br>

                        <!-- Activity duration text input -->
                        <input name="activity_duration" type="text" id="text_input" placeholder="Activity duration"><br><br>

                        <!-- Activity time period bubble check -->
                        Activity time period:
                        <input type="radio" name="activity_time_period" value="morning"> Morning
                        <input type="radio" name="activity_time_period" value="afternoon"> Afternoon
                        <input type="radio" name="activity_time_period" value="evening"> Evening
                        <br><br>

                        <!-- Activity domains table -->
                        <h4 style="display: inline;">Activity Domains</h4> 
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
                                echo "<td><input type='checkbox' name='activity_domain[]'></td>";
                                echo "</tr>";
                            }
                            ?>
                        </table>
                        <br>

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