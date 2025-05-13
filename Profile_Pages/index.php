<!-- PHP Code -->
<?php
    // Start session
    session_start();

    // Include necessary files
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Connect to the database
    $DB = new Database();
    // Check if user is logged in. If not, redirect to login page.
    $user_data = $DB->check_login();
    $user_id = $user_data['user_id'];

    // Updating all backend processes
    update_backend_data();

    // Collect volunteer data
    $number_of_volunteers = fetch_data_rows("
        SELECT COUNT(*) AS total_volunteers
        FROM Volunteers 
        WHERE `trashed` = '0'
        AND user_id = '$user_id'"
    )[0]['total_volunteers'];

    // Collect activities data
    $number_of_activities_completed = fetch_data_rows("
        SELECT COUNT(*) AS total_activities
        FROM Activities 
        WHERE activity_date < CURDATE()
        AND number_of_participants > 0
        AND user_id = '$user_id'"
    )[0]['total_activities'];

    // Collect contracts data
    $number_of_hours_completed = fetch_data_rows("
        SELECT SUM(hours_completed) AS total_hours
        FROM Contracts
        WHERE user_id = '$user_id'"
    )[0]['total_hours'];

    // Collect purchases data
    $number_of_points_spent = fetch_data_rows("
        SELECT SUM(points_spent) AS total_points
        FROM Contracts
        WHERE user_id = '$user_id'"
    )[0]['total_points'];

    // Collect volunteer interests data
    $volunteer_interest_count_data_rows = fetch_data_rows("
        SELECT interest, COUNT(*) AS total_count
        FROM Volunteer_Interests
        JOIN Volunteers ON Volunteer_Interests.volunteer_id = Volunteers.id
        WHERE Volunteers.trashed = 0
        AND Volunteers.user_id = '$user_id'
        GROUP BY interest;
    ");
    // Process the data
    $volunteer_interests_count = [];
    foreach($volunteer_interest_count_data_rows as $row){
        $volunteer_interests_count[$row['interest']] = $row['total_count'];
    }

    // Collect activity interests data
    $activity_interest_count_data_rows = fetch_data_rows("
        SELECT domain, COUNT(*) AS total_count
        FROM Activity_Domains
        JOIN Activities ON Activity_Domains.activity_id = Activities.id
        WHERE Activities.trashed = 0
        AND Activities.user_id = '$user_id'
        GROUP BY domain
        ORDER BY total_count DESC;
    ");
    // Process the data
    $activity_interests_count = [];
    foreach($activity_interest_count_data_rows as $row){
        $activity_interests_count[$row['domain']] = $row['total_count'];
    }

    // Collect volunteer availability data
    $volunteer_weekdays_count_data_rows = fetch_data_rows("
        SELECT weekday, COUNT(*) AS total_count
        FROM Volunteer_Availability
        JOIN Volunteers ON Volunteer_Availability.volunteer_id = Volunteers.id
        WHERE Volunteers.trashed = 0
        AND Volunteers.user_id = '$user_id'
        GROUP BY weekday
    ");

    // Process the data
    $volunteer_availability_count = [];
    foreach($volunteer_weekdays_count_data_rows as $row){
        $volunteer_availability_count[$row['weekday']] = $row['total_count'];
    }

    // Collect activity availability data
    $activity_weekdays_count_data_rows = fetch_data_rows("
        SELECT DAYNAME(activity_date) AS weekday, COUNT(*) AS total_count
        FROM Activities
        WHERE trashed = 0
        AND user_id = '$user_id'
        GROUP BY weekday
    ");
    // Process the data
    $activity_availability_count = [];
    foreach($activity_weekdays_count_data_rows as $row){
        $activity_availability_count[$row['weekday']] = $row['total_count'];
    }
?>



<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CivicLink | Home</title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="../Styles/style.css">
    </head>

    <!-- Plotly.js -->
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">
         

            <!-- Header Bar -->
            <?php include("../Misc/header.php"); ?>

            <!-- Cover Area -->
            <div style="width: 1500px; min-height: 400px; margin:auto;">
            
                <!-- Below Cover Area -->
                <div style="display: flex;">

                    <!-- Contact Content Area -->
                    <div id="major_rectangle">

                        <!-- Page Title -->
                        <div id="section_title" style="margin-bottom: 20px;">
                            <span style="font-size: 24px; font-weight: bold;">CivicLink: A Volunteer-Activity Management Application</span>
                        </div>

                        <!-- Page Content -->
                        <div style="display: flex;">

                            <!-- Left Side : General Data -->
                            <div class="home_page_info">
                                <h3>Database Information</h3>
                                <ul>
                                    <!-- Number of Volunteers -->
                                    <li>
                                    <span class="label">Number of Active Volunteers :</span>
                                    <span class="value"><?php echo $number_of_volunteers . " Volunteers"; ?></span>
                                    </li>

                                    <!-- Number of Activities -->
                                    <li>
                                    <span class="label">Number of Completed Activities :</span>
                                    <span class="value"><?php echo $number_of_activities_completed . " Activities"; ?></span>
                                    </li>

                                    <!-- Number of Hours -->
                                    <li>
                                    <span class="label">Number of Hours Assigned :</span>
                                    <span class="value"><?php echo $number_of_hours_completed . " Hours"; ?></span>
                                    </li>

                                    <!-- Number of Points -->
                                    <li>
                                    <span class="label">Number of Points Spent :</span>
                                    <span class="value"><?php echo $number_of_points_spent . " Points"; ?></span>
                                    </li>

                                    <!-- Export to CSV -->
                                    <li>
                                    <span class="label">Export to CSV:</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Right Side : Pie Charts-->
                            <div>
                                <!-- 1st row: Volunteer Interests and Activity Interests -->
                                <div style="display: flex; border-bottom: 1px solid #ddd;">
                                    <div id="volunteer_interests_Plot" style="width:100%; max-width:650px; max-height: 365px;"></div>
                                    <div id="activity_interests_Plot" style="width:100%; max-width:450px"></div>
                                </div>

                                <!-- 2nd row: Volunteer Availability and Activity Availability -->
                                <div style="display: flex">
                                    <div id="volunteer_availability_Plot" style="width:100%; max-width:650px; max-height: 365px;"></div>
                                    <div id="activity_availability_Plot" style="width:100%; max-width:450px"></div>
                                </div>
                            </div>

                        </div>

                        <!-- JavaScript code for Plotly.js -->
                        <script>
                            // Database arrays
                            const xArray_interests = ["Organization of community events", "Library support", "Help in the community store", "Support in the community grocery store", "Cleaning and maintenance of public spaces", "Participation in urban gardening projects"];
                            const xArray_weekdays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

                            const colorsForInterests = [
                                "#ff7f0e", // orange
                                "#2ca02c", // green
                                "#d62728", // red
                                "#9467bd", // violet
                                "#1f77b4", // blue
                                "#60C9D7"  //  light blue
                            ];

                                const colorsForAvailability = [
                                "#5B8FF9", // blue
                                "#5AD8A6", // green
                                "#F6BD16", // yellow-orange
                                "#E8684A", // red
                                "#6F5EF9", // purple
                                "#FF86B4"  // pink
                            ];


                            // Processing the data for the pie charts
                            const volunteer_interests = <?php echo json_encode($volunteer_interests_count); ?>;
                            const volunteer_interests_count = xArray_interests.map(label => volunteer_interests[label] || 0);

                            const activity_interests = <?php echo json_encode($activity_interests_count); ?>;
                            const activity_interests_count = xArray_interests.map(label => activity_interests[label] || 0); 

                            const volunteer_availability = <?php echo json_encode($volunteer_availability_count); ?>;
                            const volunteer_weekdays_count = xArray_weekdays.map(label => volunteer_availability[label] || 0);

                            const activity_availability = <?php echo json_encode($activity_availability_count); ?>;
                            const activity_weekdays_count = xArray_weekdays.map(label => activity_availability[label] || 0);


                            // Plot 1: Volunteer Interests Distribution
                            Plotly.newPlot("volunteer_interests_Plot", [{labels:xArray_interests, values:volunteer_interests_count, hole:.4, type:"pie", sort: false, marker: {colors: colorsForInterests}}], {
                                title: {text: "Volunteer Interests Distribution", x: 0.825, xanchor: "right"}, 
                                legend: {orientation: "v",x: -1.3, y: 0.5, itemclick: false, itemdoubleclick: false},
                                margin: {b: 10}
                            });

                            // Plot 2: Activity Interests Distribution
                            Plotly.newPlot("activity_interests_Plot", [{labels:xArray_interests, values:activity_interests_count, hole:.4, type:"pie", sort: false, marker: {colors: colorsForInterests}, showlegend: false}], {
                                title:"Activity Interests Distribution",
                                margin: {b: 10}
                            });

                            // Plot 3: Volunteer Availability Distribution
                            Plotly.newPlot("volunteer_availability_Plot", [{labels:xArray_weekdays, values:volunteer_weekdays_count, hole:.4, type:"pie", sort: false, marker: {colors: colorsForAvailability}}], {
                                title: {text: "Volunteer Availability Distribution", x: 0.835, xanchor: "right"}, 
                                legend: {orientation: "v",x: -1.3, y: 0.5,itemclick: false, itemdoubleclick: false },
                                margin: {b: 10}
                            });

                            // Plot 4: Activity Availability Distribution
                            Plotly.newPlot("activity_availability_Plot", [{labels:xArray_weekdays, values:activity_weekdays_count, hole:.4, type:"pie", sort: false, marker: {colors: colorsForAvailability}, showlegend: false}], { 
                                title:"Activity Availability Distribution",
                                margin: {b: 10}
                            });

                        </script>

                    </div>
                </div>

            </div>
        </div>
    </body>
</html>



<!-- CSS -->
<style>
    .home_page_info {
    background: white;
    border-radius: 8px;
    padding: 20px;
    max-width: 500px;
    margin: 0px auto;
    }

    .home_page_info h3 {
    color: #405d9b;
    text-align: center;
    margin-bottom: 20px;
    }

    .home_page_info ul {
    list-style: none;
    padding: 0;
    margin: 0;
    }

    .home_page_info li {
    display: flex;
    flex-direction: column;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
    }

    .home_page_info li:last-child {
    border-bottom: none;
    }

    .home_page_info .label {
    font-weight: bold;
    font-size: 16px;
    color: #333;
    }

    .home_page_info .value {
    font-size: 18px;
    color: #555;
    margin-top: 5px;
    }
</style>