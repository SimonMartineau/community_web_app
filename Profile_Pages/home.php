<?php

    // Include classes
    include("../Classes/connect.php");
    include("../Classes/functions.php");

    // Updating all backend processes
    update_backend_data();

    // Collect volunteer data
    $number_of_volunteers = fetch_data("
        SELECT COUNT(*) AS total_volunteers
        FROM Volunteers 
        WHERE `trashed` = '0'"
    )[0]['total_volunteers'];

    // Collect activities data
    $number_of_activities_completed = fetch_data("
        SELECT COUNT(*) AS total_activities
        FROM Activities 
        WHERE activity_date < CURDATE()
        AND number_of_participants > 0"
    )[0]['total_activities'];

    // Collect contracts data
    $number_of_hours_completed = fetch_data("
        SELECT SUM(hours_completed) AS total_hours
        FROM Contracts"
    )[0]['total_hours'];

    // Collect purchases data
    $number_of_points_spent = fetch_data("
        SELECT SUM(points_spent) AS total_points
        FROM Contracts"
    )[0]['total_points'];

    $volunteer_interest_count_data = fetch_data("
        SELECT interest, COUNT(*) AS total_count
        FROM Volunteer_Interests
        JOIN Volunteers ON Volunteer_Interests.volunteer_id = Volunteers.id
        WHERE Volunteers.trashed = 0
        GROUP BY interest;
    ");

    $volunteer_interests_count = [];

    foreach($volunteer_interest_count_data as $row){
        $volunteer_interests_count[$row['interest']] = $row['total_count'];
    }

    $activity_interest_count_data = fetch_data("
        SELECT domain, COUNT(*) AS total_count
        FROM Activity_Domains
        JOIN Activities ON Activity_Domains.activity_id = Activities.id
        WHERE Activities.trashed = 0
        GROUP BY domain
        ORDER BY total_count DESC;
    ");

    $activity_interests_count = [];

    foreach($activity_interest_count_data as $row){
        $activity_interests_count[$row['domain']] = $row['total_count'];
    }

    $volunteer_weekdays_count_data = fetch_data("
        SELECT weekday, COUNT(*) AS total_count
        FROM Volunteer_Availability
        JOIN Volunteers ON Volunteer_Availability.volunteer_id = Volunteers.id
        WHERE Volunteers.trashed = 0
        GROUP BY weekday
    ");

    $volunteer_availability_count = [];

    foreach($volunteer_weekdays_count_data as $row){
        $volunteer_availability_count[$row['weekday']] = $row['total_count'];
    }

    $activity_weekdays_count_data = fetch_data("
        SELECT DAYNAME(activity_date) AS weekday, COUNT(*) AS total_count
        FROM Activities
        WHERE trashed = 0
        GROUP BY weekday
    ");

    $activity_availability_count = [];

    foreach($activity_weekdays_count_data as $row){
        $activity_availability_count[$row['weekday']] = $row['total_count'];
    }

    

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home | Give and Receive</title>
        <link rel="stylesheet" href="../style.css">
    </head>

    <style></style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>



    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <!-- Header bar -->
        <?php include("../Misc/header.php"); ?>

        <!-- Cover area -->
        <div style="width: 1500px; min-height: 400px; margin:auto;">
            <div>
                <br>
            </div>
        
            <!-- Below cover area -->
            <div style="display: flex;">

                <!-- Contact content area -->
                <div id="major_rectangle">

                    <!-- Section title of contact section -->
                    <div id="section_title" style="margin-bottom: 20px;">
                        <span style="font-size: 24px; font-weight: bold;">Volunteer-Activity Management Application</span>
                    </div>

                    <div style="display: flex; border-bottom: 1px solid #000; padding-bottom: 20px;">
                        <div>
                        <h3 style="margin-top: 20px; color: #405d9b;">Database Information</h3>
                        <ul style="line-height: 1.8; font-size: 16px; margin-left: 20px; color: #333;">
                            <li><strong>Number of Volunteers:</strong> <?php echo $number_of_volunteers;?> </li>
                            <li><strong>Number of Activities Completed:</strong> <?php echo $number_of_activities_completed;?> </li> 
                            <li><strong>Number of Hours Completed:</strong> <?php echo $number_of_hours_completed;?> </li>
                            <li><strong>Number of Points Spent:</strong> <?php echo $number_of_points_spent;?> </li>
                        </ul>
                        </div>

                        <div>
                            <div style="display: flex; border-bottom: 1px solid #000; padding-bottom: 20px;">
                                <div id="chartLegend"></div>
                                <canvas id="volunteer_interests_Plot" style="width:100%;max-width:500px"></canvas>
                                <canvas id="activity_interests_Plot" style="width:100%;max-width:500px"></canvas>
                            </div>
                            <div style="display: flex;">
                                <canvas id="volunteer_availability_Plot" style="width:100%;max-width:500px"></canvas>
                                <canvas id="activity_availability_Plot" style="width:100%;max-width:500px"></canvas>
                            </div>
                        </div>
                    </div>
                    

                    <script>
                        const barColors = ["#b91d47","#00aba9","#2b5797","#e8c3b9","#1e7145","#f0a500","#f472d0"];
                        const barColors2 = ["#c45850","#4bc0c0","#36a2eb","#f7786b","#f0a500","#f472d0", "#b91d47"];
                        const xArray_interests = ["Organization of community events", "Library support", "Help in the community store", "Support in the community grocery store", "Cleaning and maintenance of public spaces", "Participation in urban gardening projects"];
                        const xArray_weekdays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

                        const volunteer_interests = <?php echo json_encode($volunteer_interests_count); ?>;
                        const volunteer_interests_count = xArray_interests.map(label => volunteer_interests[label] || 0);

                        const activity_interests = <?php echo json_encode($activity_interests_count); ?>;
                        const activity_interests_count = xArray_interests.map(label => activity_interests[label] || 0); 

                        const volunteer_availability = <?php echo json_encode($volunteer_availability_count); ?>;
                        const volunteer_weekdays_count = xArray_weekdays.map(label => volunteer_availability[label] || 0);

                        const activity_availability = <?php echo json_encode($activity_availability_count); ?>;
                        const activity_weekdays_count = xArray_weekdays.map(label => activity_availability[label] || 0);


                        // Plot 1: Volunteer Interests Distribution
                        new Chart("volunteer_interests_Plot", {
                            type: "doughnut",
                            data: {labels: xArray_interests, datasets: [{backgroundColor: barColors, data: volunteer_interests_count}]},
                            options: {legend: {display: false}, title: {display: true, text: "Volunteer Interests Distribution"}}
                        });

                        // Plot 2: Activity Interests Distribution
                        new Chart("activity_interests_Plot", {
                            type: "doughnut",
                            data: {labels: xArray_interests, datasets: [{backgroundColor: barColors, data: activity_interests_count}]},
                            options: {legend: {display: false},title: {display: true,text: "Activity Interests Distribution"}}
                        });

                        // Plot 3: Volunteer Availability Distribution
                        new Chart("volunteer_availability_Plot", {
                            type: "doughnut",
                            data: {labels: xArray_weekdays,datasets: [{backgroundColor: barColors2, data: volunteer_weekdays_count}]},
                            options: {legend: {display: false},title: {display: true, text: "Volunteer Availability Distribution"}}
                        });

                        // Plot 4: Activity Availability Distribution
                        new Chart("activity_availability_Plot", {
                            type: "doughnut",
                            data: {labels: xArray_weekdays, datasets: [{backgroundColor: barColors2, data: activity_weekdays_count}]},
                            options: {legend: {display: false},title: {display: true,text: "Activity Availability Distribution"}}
                        });

                    </script>

                </div>

            </div>
            
        </div>
        
    </body>
</html>

