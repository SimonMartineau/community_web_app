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
        SELECT DAYNAME(activity_date) AS weekday, SUM(number_of_places) AS total_count
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
        <link rel="stylesheet" href="../Styles/home_page.css">
    </head>

    <!-- Plotly.js -->
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <script src="../JavaScript/plot.js"></script>
         
            <!-- Header Bar -->
            <?php include("../Misc/header.php"); ?>

            <!-- Cover Area -->
            <div style="width: 1500px; min-height: 400px; margin:auto;">
            
                <!-- Below Cover Area -->
                <div style="display: flex;">

                    <!-- Contact Content Area -->
                    <div id="major_rectangle" style="height: 800px;">

                        <!-- Page Title -->
                        <div id="section_title" style="margin-bottom: 20px;">
                            <span style="font-size: 24px; font-weight: bold;">CivicLink: A Volunteer & Activity Management Application</span>
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
                                </ul>
                            </div>

                            <!-- Right Side : Bar Graphs -->
                            <div>                            
                                <!-- 1st row: Volunteer & Activity Interests Distribution  -->
                                <div style="width: 1100px; margin: 0 auto; height:350px;">
                                    <div id="volunteer_activity_interests_plot" style="width:100%; height:350px;"></div>
                                </div>

                                <!-- 2nd row: Volunteer & Activity Availability Distribution -->
                                <div style="width: 1100px; margin: 0 auto; height:350px;">
                                    <div id="volunteer_activity_availability_plot" style="width:100%; height:350px;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- JavaScript code for Plotly.js -->
                        <script>
                            // Database arrays
                            const xArray_interests = ["Organization of community events", "Library support", "Help in the community store", "Support in the community grocery store", "Cleaning and maintenance of public spaces", "Participation in urban gardening projects"];
                            const xArray_weekdays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

                            // Labels for the bar graphs
                            const xArray_interests_labels = ["Organization of<br>community events", "Library support", "Help in the community<br>store", "Support in the<br>community  grocery store", "Cleaning and maintenance<br>of public spaces", "Participation in urban<br>gardening projects"];
                            const xArray_weekdays_labels = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];


                            const colorsForInterests = [
                                "#ff7f0e", // orange
                                "#2ca02c", // green
                                "#d62728", // red
                                "#9467bd", // violet
                                "#1f77b4", // blue
                                "#60C9D7"  //  light blue
                            ];

                            // Light Green for volunteer
                            const volunteer_bar_color = Array(7).fill("#5AD8A6");


                            // Light blue for activity
                            const activity_bar_color = Array(7).fill("#5B8FF9");


                            const colorsForAvailability = [
                                "#5B8FF9", // blue
                                "#5AD8A6", // green
                                "#F6BD16", // yellow-orange
                                "#E8684A", // red
                                "#6F5EF9", // purple
                                "#FF86B4"  // pink
                            ];


                            // Processing the data for the bar graphs
                            const volunteer_interests = <?php echo json_encode($volunteer_interests_count); ?>;
                            const volunteer_interests_count = xArray_interests.map(label => volunteer_interests[label] || 0);

                            const activity_interests = <?php echo json_encode($activity_interests_count); ?>;
                            const activity_interests_count = xArray_interests.map(label => activity_interests[label] || 0); 

                            const volunteer_availability = <?php echo json_encode($volunteer_availability_count); ?>;
                            const volunteer_weekdays_count = xArray_weekdays.map(label => volunteer_availability[label] || 0);

                            const activity_availability = <?php echo json_encode($activity_availability_count); ?>;
                            const activity_weekdays_count = xArray_weekdays.map(label => activity_availability[label] || 0);

                            
                            var volunteer_interest_trace = {
                                x: xArray_interests_labels,
                                y: volunteer_interests_count,
                                name: 'Volunteer',
                                type: 'bar',
                                marker: {
                                    color: volunteer_bar_color,
                                    opacity: 0.8
                                }
                            };

                            var activity_interest_trace = {
                                x: xArray_interests_labels,
                                y: activity_interests_count,
                                name: 'Activity',
                                type: 'bar',
                                marker: {
                                    color: activity_bar_color,
                                    opacity: 0.8
                                }
                            };

                            Plotly.newPlot("volunteer_activity_interests_plot", [volunteer_interest_trace, activity_interest_trace], {
                            title: {
                                text: "Volunteer & Activity Interests History",
                                x: 0.5
                            },
                            barmode: 'group',        // side-by-side bars
                            showlegend: true,
                            legend: {
                                orientation: 'v',
                                x: 0.9,
                                y: 1.2,
                                itemclick: false,
                                itemdoubleclick: false
                            },
                            xaxis: {
                                title: 'Interests',
                                tickangle: 0,
                                automargin: true
                            },
                            yaxis: {
                                title: 'Count',
                            },
                            margin: {
                                b: 40,
                                l: 100,
                                t: 80
                            },
                            width: 1100,
                            height: 350
                            });


                            var volunteer_availability_trace = {
                                x: xArray_weekdays_labels,
                                y: volunteer_weekdays_count,
                                name: 'Volunteer',
                                type: 'bar',
                                marker: {
                                    color: volunteer_bar_color,
                                    opacity: 0.8
                                }
                            };

                            var activity_availability_trace = {
                                x: xArray_weekdays_labels,
                                y: activity_weekdays_count,
                                name: 'Activity',
                                type: 'bar',
                                marker: {
                                    color: activity_bar_color,
                                    opacity: 0.8
                                }
                            };

                            Plotly.newPlot("volunteer_activity_availability_plot", [volunteer_availability_trace, activity_availability_trace], {
                            title: {
                                text: "Volunteer & Activity Availability History",
                                x: 0.5
                            },
                            barmode: 'group',        // side-by-side bars
                            showlegend: true,
                            legend: {
                                orientation: 'v',
                                x: 0.9,
                                y: 1.2,
                                itemclick: false,
                                itemdoubleclick: false
                            },
                            xaxis: {
                                title: 'Weekday',
                                tickangle: 0,
                                automargin: true
                            },
                            yaxis: {
                                title: 'Count'
                            },
                            margin: {
                                b: 40,
                                l: 100,
                                t: 80
                            },
                            width: 1100,
                            height: 350
                            });

                        </script>

                    </div>
                </div>

            </div>
        </div>
    </body>
</html>