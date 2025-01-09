<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MyBook | Profile</title>
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
        
            <!-- Below cover area -->
            <div style="display: flex;">

                <!-- Contact content area -->
                <div id="post_bar" style="padding: 20px; background-color: #f9f9f9; border-radius: 8px;">
                    <!-- Section title of contact section -->
                    <div id="section_title" style="margin-bottom: 20px;">
                        <span style="font-size: 24px; font-weight: bold;">Contact Us</span>
                    </div>

                    <p style="line-height: 1.6; font-size: 16px; color: #333;">
                        We're here to help! If you have any questions, feedback, or need assistance using <strong>Give and Receive</strong>, don’t hesitate to get in touch with us. Our team is dedicated to ensuring your experience on the platform is seamless and fulfilling.
                    </p>

                    <h3 style="margin-top: 20px; color: #405d9b;">How to Reach Us</h3>
                    <ul style="line-height: 1.8; font-size: 16px; margin-left: 20px; color: #333;">
                        <li><strong>Email Support:</strong> Send us an email at <a href="mailto:support@giveandreceive.com" style="color: #405d9b; text-decoration: none;">support@giveandreceive.com</a>. We aim to respond within 24 hours.</li>
                        <li><strong>Phone:</strong> Call us at <strong>(555) 123-4567</strong>. Our support lines are open Monday to Friday, 9 AM to 5 PM.</li>
                        <li><strong>Contact Form:</strong> Use the contact form below to send us your queries or feedback. Simply fill in the required details and hit "Submit."</li>
                    </ul>
                </div>
            </div>
            
        </div>
        
    </body>
</html>