<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact | Give and Receive</title>
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

        #login_bar{
            background-color: white; 
            width:800px; 
            margin: auto; 
            margin-top: 50px;
            padding: 10px;
            padding-top: 50px;
            text-align: center;
            font-weight: bold;

        }

        #text{
            height: 40px;
            width: 300px;
            border-radius: 4px;
            border: solid 1px #ccc;
            padding: 4px;
            font-size: 14px;

        }

        #button{
            width: 300px;
            height: 40px;
            border-radius: 8px;
            border: none;
            background-color: rgb(59,89,152);
            color: white;
            font-weight: bold;
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

            <div id="login_bar">
                <div id="section_title" style="margin-bottom: 20px;">
                    <span style="font-size: 24px; font-weight: bold;">Add Volunteer Form</span>
                </div>
                <br><br>

                <form method="post" action="signup.php">
                    <input name="first_name" type="text" id="text" placeholder="First name"><br><br>
                    <input name="last_name" type="text" id="text" placeholder="Last name"><br><br>
                    <span style="font-weight: normal">Gender:</span><br>
                    <select name="gender" id="text">
                        <option></option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select><br><br>
                    <input name="email" type="text" id="text" placeholder="Email"><br><br>
                    <input name="password" type="password" id="text" placeholder="Password"><br><br>
                    <input name="password_re" type="password" id="text" placeholder="Retype-Password"><br><br>
                    <input type="submit" id="button" value="Sign up"><br><br><br>
                </form>
            </div>
        </div>
            
        
    </body>
</html>