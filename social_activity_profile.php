<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Volunteer Profile | Give and Receive</title>
    </head>

    <style>
        #post_bar{
            margin-top: 20px;
            background-color: white;
            padding: 10px;
            min-height: 400px; 
            flex:1.5; 
            padding-left: 20px; 
            padding-right: 0px;
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

    </style>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- Add edit social actiivty button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="volunteer_edit_data.php" style="text-decoration: none; display: inline-block;">
                    <button style="
                        padding: 10px 20px; 
                        background-color: #405d9b; 
                        color: white; 
                        border: none; 
                        border-radius: 15px; 
                        font-size: 16px; 
                        cursor: pointer;">
                        Edit Data
                    </button>
                </a>
            </div>

            <!-- Add delete social activity button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="purchase.php" style="text-decoration: none; display: inline-block;">
                    <button style="
                        padding: 10px 20px; 
                        background-color: #405d9b; 
                        color: white; 
                        border: none; 
                        border-radius: 15px; 
                        font-size: 16px; 
                        cursor: pointer;">
                        Delete Social Activity
                    </button>
                </a>
            </div>
                    
            <!-- Below cover area -->
            <div style="display: flex;">

                <!-- Contact content area -->
                <div id="post_bar" style="padding: 20px; background-color: #f9f9f9; border-radius: 8px;">
                    <!-- Section title of contact section -->
                    <div id="section_title" style="margin-bottom: 20px;">
                        <span style="font-size: 24px; font-weight: bold;">Volunteer Info</span>
                    </div>

                    
                </div>
            </div>
            
        </div>
        
    </body>
</html>