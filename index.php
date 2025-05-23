<!-- PHP Code -->
<?php
    // Include header
    include(__DIR__ . "/Header/header.php");

    // Include necessary files
    include(__DIR__ . "/Classes/connect.php");
    include(__DIR__ . "/Classes/functions.php");

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

    // Collect activities data for the last 30 days
    $number_of_activities_completed_in_month = fetch_data_rows("
        SELECT COUNT(*) AS total_activities
        FROM Activities 
        WHERE activity_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()
        AND number_of_participants > 0
        AND user_id = '$user_id'"
    )[0]['total_activities'];

    // Collect activity time data
    $number_of_hours_completed = fetch_data_rows("
        SELECT
        COALESCE(
            SUM(activity_duration * number_of_participants),
            0
        ) AS hours_completed
        FROM Activities 
        WHERE activity_date < CURDATE()
        AND number_of_participants > 0
        AND user_id = '$user_id'"
    )[0]['hours_completed'];

    // Collect activity time data for the last 30 days
    $number_of_hours_completed_in_month = fetch_data_rows("
        SELECT
        COALESCE(
            SUM(activity_duration * number_of_participants),
            0
        ) AS hours_completed
        FROM Activities 
        WHERE activity_date = DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        AND number_of_participants > 0
        AND user_id = '$user_id'
    ")[0]['hours_completed'];

    // Collect purchases data
    $number_of_points_spent = fetch_data_rows("
        SELECT
        COALESCE(
            SUM(total_cost),
            0
        ) AS total_points_spent
        FROM Purchases
        WHERE user_id = '$user_id'"
    )[0]['total_points_spent'];

    // Collect purchases data for the last 30 days
    $number_of_points_spent_in_month = fetch_data_rows("
        SELECT
        COALESCE(
            SUM(total_cost),
            0
        ) AS total_points_spent
        FROM Purchases
        WHERE purchase_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()
        AND user_id = '$user_id'"
    )[0]['total_points_spent'];

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

    // Collect activity interests data for the last 30 days
    $activity_interest_count_data_rows = fetch_data_rows("
        SELECT domain, SUM(number_of_places) AS total_count
        FROM Activity_Domains
        JOIN Activities ON Activity_Domains.activity_id = Activities.id
        WHERE Activities.trashed = 0
        AND Activities.activity_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()
        AND Activities.user_id = '$user_id'
        GROUP BY domain
        ORDER BY total_count DESC;
    ");
    // Process the data
    $activity_interests_count = [];
    foreach($activity_interest_count_data_rows as $row){
        // Assigning the domain as the key and the total count as the value
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

    // Collect activity availability data for the last 30 days
    $activity_weekdays_count_data_rows = fetch_data_rows("
        SELECT DAYNAME(activity_date) AS weekday, SUM(number_of_places) AS total_count
        FROM Activities
        WHERE trashed = 0
        AND activity_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()
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
        <title><?= __('CivicLink | Home') ?></title>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <link rel="stylesheet" href="/CivicLink_Web_App/Styles/style.css">
        <link rel="stylesheet" href="/CivicLink_Web_App/Styles/home_page.css">
    </head>

    <!-- Plotly.js -->
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>

    <body style="font-family: sans-serif ; background-color: #d0d8e4;">

        <script src="/CivicLink_Web_App/JavaScript/plot.js"></script>

            <!-- Cover Area -->
            <div style="width: 1600px; min-height: 400px; margin:auto;">
            
                <!-- Below Cover Area -->
                <div style="display: flex;">

                    <!-- Contact Content Area -->
                    <div id="major_rectangle" style="height: 800px;">

                        <!-- Page Title -->
                        <div id="section_title" style="margin-bottom: 20px;">
                            <span style="font-size: 24px; font-weight: bold;"><?= __('CivicLink: A Volunteer & Activity Management Application') ?></span>
                        </div>

                        <!-- Page Content -->
                        <div style="display: flex;">

                            <!-- Left Side : General Data -->
                            <div class="home_page_info">
                                <h3><?= __('Database Information') ?></h3>
                                <ul>
                                    <!-- Number of Volunteers -->
                                    <li>
                                        <span class="label"><?= __('Number of Active Volunteers:') ?></span>
                                        <span class="value">
                                            <?php
                                                $volunteer_label = ($number_of_volunteers == 1) ? __('Volunteer') : __('Volunteers');
                                                echo $number_of_volunteers . ' ' . $volunteer_label;
                                            ?>
                                        </span>
                                    </li>

                                    <!-- Last 30 Days Subheader-->
                                    <h4><?= __('Last 30 Days') ?></h4>

                                    <!-- Number of Activities Completed in the last 30 days -->
                                    <li>
                                        <span class="label"><?= __('Number of Activities Completed:') ?></span>
                                        <span class="value">
                                            <?php
                                                $activity_label = ($number_of_activities_completed_in_month == 1) ? __('Activity') : __('Activities');
                                                echo $number_of_activities_completed_in_month . ' ' . $activity_label;
                                            ?>
                                        </span>
                                    </li>

                                    <!-- Number of Points Spent in the last 30 days -->
                                    <li>
                                        <span class="label"><?= __('Number of Points Spent:') ?></span>
                                        <span class="value">
                                            <?php
                                                $point_label = ($number_of_points_spent_in_month == 1) ? __('Point') : __('Points');
                                                echo $number_of_points_spent_in_month . ' ' . $point_label;
                                            ?>
                                        </span>
                                    </li>

                                    <!-- All Time Subheader-->
                                    <h4><?= __('All Time') ?></h4>

                                    <!-- Number of Activities -->
                                    <li>
                                        <span class="label"><?= __('Number of Activities Completed:') ?></span>
                                        <span class="value">
                                            <?php
                                                $activity_label = ($number_of_activities_completed == 1) ? __('Activity') : __('Activities');
                                                echo $number_of_activities_completed . ' ' . $activity_label;
                                            ?>
                                        </span>
                                    </li>

                                    <!-- Number of Points -->
                                    <li>
                                        <span class="label"><?= __('Number of Points Spent:') ?></span>
                                        <span class="value">
                                            <?php
                                                $point_label = ($number_of_points_spent == 1) ? __('Point') : __('Points');
                                                echo $number_of_points_spent . ' ' . $point_label;
                                            ?>
                                        </span>
                                    </li>
                                    <br>

                                    <!-- Download Database Link -->
                                    <li>
                                        <a href="/CivicLink_Web_App/Classes/export_database.php" class="reset-link" download="CivicLink_Web_App.xlsx">
                                            <?php echo __('Download Database'); ?>
                                        </a>
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
                            // Language variable to determine the language of the page
                            const lang = "<?php echo $_SESSION['lang']; ?>";

                            // Initalize the labels for the bar graphs
                            let xArray_interests_labels;
                            let xArray_weekdays_labels;
                            let interest_plot_title;
                            let availability_plot_title;
                            let xaxis_interest_title;
                            let xaxis_availability_title;
                            let yaxis_interest_title;
                            let yaxis_availability_title;
                            let volunteer_legend_title;
                            let activity_legend_title;


                            // Database arrays. This is to associate the data with the labels.
                            const xArray_interests = [
                                'Organization of community events', 
                                'Library support', 
                                'Help in the community store', 
                                'Support in the community grocery store', 
                                'Cleaning and maintenance of public spaces', 
                                'Participation in urban gardening projects'
                            ];
                            const xArray_weekdays = [
                                'Monday', 
                                'Tuesday', 
                                'Wednesday', 
                                'Thursday', 
                                'Friday', 
                                'Saturday', 
                                'Sunday'
                            ];


                            // Labels for the bar graphs, translation done here due to issues with Plotly.js.
                            if (lang === 'en') {
                                // English labels
                                xArray_interests_labels = [
                                    "Organization of<br>community events", 
                                    "Library support", 
                                    "Help in the community<br>store", 
                                    "Support in the<br>community grocery store", 
                                    "Cleaning and maintenance<br>of public spaces", 
                                    "Participation in urban<br>gardening projects"
                                ];
                                xArray_weekdays_labels = [
                                    "Monday", 
                                    "Tuesday", 
                                    "Wednesday", 
                                    "Thursday", 
                                    "Friday", 
                                    "Saturday", 
                                    "Sunday"
                                ];
                                interest_plot_title = "Volunteer & Activity Interests In The Past 30 Days";
                                availability_plot_title = "Volunteer & Activity Availability In The Past 30 Days";
                                xaxis_interest_title = "Interests";
                                xaxis_availability_title = "Weekday";
                                yaxis_interest_title = "Count";
                                yaxis_availability_title = "Count";
                                volunteer_legend_title = "Volunteers";
                                activity_legend_title = "Activity Capacity";
                            } else {
                                // Portuguese labels
                                xArray_interests_labels = [
                                    "Organização de<br>eventos comunitários", 
                                    "Apoio à biblioteca", 
                                    "Ajuda na<br>loja comunitária", 
                                    "Apoio na<br>mercearia comunitária", 
                                    "Limpeza e manutenção<br>de espaços públicos", 
                                    "Participação em<br>projetos de<br>jardinagem urbana"
                                ];
                                xArray_weekdays_labels = [
                                    "Segunda-feira", 
                                    "Terça-feira", 
                                    "Quarta-feira", 
                                    "Quinta-feira", 
                                    "Sexta-feira", 
                                    "Sábado", 
                                    "Domingo"
                                ];
                                interest_plot_title = "Interesses em Voluntariado e Atividades nos Últimos 30 Dias";
                                availability_plot_title = "Disponibilidade para Voluntariado e Atividades nos Últimos 30 Dias";
                                xaxis_interest_title = "Interesses";
                                xaxis_availability_title = "Dia da Semana";
                                yaxis_interest_title = "Contagem";
                                yaxis_availability_title = "Contagem";
                                volunteer_legend_title = "Voluntários";
                                activity_legend_title = "Capacidade<br>da Atividade";

                            }
                    
                                
                            // Light Green for volunteer
                            const volunteer_bar_color = Array(7).fill("#5AD8A6");
                            
                            // Light blue for activity
                            const activity_bar_color = Array(7).fill("#5B8FF9");


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
                                name: volunteer_legend_title,
                                type: 'bar',
                                marker: {
                                    color: volunteer_bar_color,
                                    opacity: 0.8
                                }
                            };

                            var activity_interest_trace = {
                                x: xArray_interests_labels,
                                y: activity_interests_count,
                                name: activity_legend_title,
                                type: 'bar',
                                marker: {
                                    color: activity_bar_color,
                                    opacity: 0.8
                                }
                            };

                            Plotly.newPlot("volunteer_activity_interests_plot", [volunteer_interest_trace, activity_interest_trace], {
                            title: {
                                text: interest_plot_title,
                                x: 0.5
                            },
                            barmode: 'group',
                            showlegend: true,
                            legend: {
                                orientation: 'v',
                                x: 0.9,
                                y: 1.2,
                                itemclick: false,
                                itemdoubleclick: false
                            },
                            xaxis: {
                                title: xaxis_interest_title,
                                tickangle: 0,
                                automargin: true
                            },
                            yaxis: {
                                title: yaxis_interest_title,
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
                                name: volunteer_legend_title,
                                type: 'bar',
                                marker: {
                                    color: volunteer_bar_color,
                                    opacity: 0.8
                                }
                            };

                            var activity_availability_trace = {
                                x: xArray_weekdays_labels,
                                y: activity_weekdays_count,
                                name: activity_legend_title,
                                type: 'bar',
                                marker: {
                                    color: activity_bar_color,
                                    opacity: 0.8
                                }
                            };

                            Plotly.newPlot("volunteer_activity_availability_plot", [volunteer_availability_trace, activity_availability_trace], {
                            title: {
                                text: availability_plot_title,
                                x: 0.5
                            },
                            barmode: 'group',
                            showlegend: true,
                            legend: {
                                orientation: 'v',
                                x: 0.9,
                                y: 1.2,
                                itemclick: false,
                                itemdoubleclick: false
                            },
                            xaxis: {
                                title: xaxis_availability_title,
                                tickangle: 0,
                                automargin: true
                            },
                            yaxis: {
                                title: yaxis_availability_title,
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