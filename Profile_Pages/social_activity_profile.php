<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Activity Profile | Give and Receive</title>
        <link rel="stylesheet" href="../style.css">
    </head>

    <style></style>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <br>

            <!-- Add edit social actiivty button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Edit_Form_Pages/edit_volunteer_data.php" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Edit Data
                    </button>
                </a>
            </div>

            <!-- Add delete social activity button -->
            <div style="text-align: right; padding: 10px 20px;display: inline-block;">
                <a href="../Profile_Pages/purchase.php" style="text-decoration: none; display: inline-block;">
                    <button id="submenu_button">
                        Delete Social Activity
                    </button>
                </a>
            </div>
                    
            <!-- Below cover area -->
            <div style="display: flex;">

                <!-- Contact content area -->
                <div id="major_rectangle">
                    <!-- Section title of contact section -->
                    <div id="section_title" style="margin-bottom: 20px;">
                        <span style="font-size: 24px; font-weight: bold;">Volunteer Info</span>
                    </div>

                    
                </div>
            </div>
            
        </div>
        
    </body>
</html>