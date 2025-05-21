<?php

// Class to add activity to database
class Add_Activity{

    // Error messages for each form field
    public $activity_name_error_mes = "";
    public $number_of_places_error_mes = "";
    public $activity_duration_error_mes = "";
    public $activity_dates_error_mes = "";
    public $activity_time_periods_error_mes = "";
    public $activity_domains_error_mes = "";
    public $entry_clerk_error_mes = "";
    public $activity_id = "";
    public $user_id;

    public function __construct($user_id) {
        $this->user_id = $user_id;
    }


    // Analyses data sent by user
    public function evaluate($data){

        // Initialise error contract variable
        $error = false;

        // Check activity name
        if (isset($_POST['activity_name'])) {
            $value = $_POST['activity_name'];
            if (empty($value)) {
                $this->activity_name_error_mes = __('*Activity name is empty.<br>');
                $error = true; // There is an error
            }
        }

        // Check activity number of participants
        if (isset($_POST['number_of_places'])) {
            $value = $_POST['number_of_places'];
            if (empty($value)) {
                $this->number_of_places_error_mes = __('*Number of places is empty.<br>');
                $error = true; // There is an error
            } elseif (!preg_match("/^[0-9]*$/", $value)) {
                $this->number_of_places_error_mes = __('*Please enter a number.<br>');
                $error = true; // There is an error
            } elseif ($value < 0) {
                $this->number_of_places_error_mes = __('*Please enter a positive number.<br>');
                $error = true; // There is an error
            } elseif ($value > 1000) {
                $this->number_of_places_error_mes = __('*Please enter a number less than 1000.<br>');
                $error = true; // There is an error
            }
        }

        // Check activity duration
        if (isset($_POST['activity_duration'])) {
            $value = $_POST['activity_duration'];
            if (empty($value)) {
                $this->activity_duration_error_mes = __('*Activity duration is empty.<br>');
                $error = true; // There is an error
            } elseif (!preg_match("/^[0-9]*$/", $value)) {
                $this->activity_duration_error_mes = __('*Please enter a number.<br>');
                $error = true; // There is an error
            } elseif ($value < 0) {
                $this->activity_duration_error_mes = __('*Please enter a positive number.<br>');
                $error = true; // There is an error
            } elseif ($value > 1000) {
                $this->activity_duration_error_mes = __('*Please enter a number less than 1000.<br>');
                $error = true; // There is an error
            }
        }

        // Check activity dates
        if (isset($_POST['activity_dates'])) {
            $value = $_POST['activity_dates'];
            if (empty($value)) {
                $this->activity_dates_error_mes = __('*Activity dates are empty.<br>');
                $error = true; // There is an error
            }
        }

        // Check activity time period
        if (isset($_POST['activity_time_periods'])) {
            $value = $_POST['activity_time_periods'];
            if (empty($value)) {
                $this->activity_time_periods_error_mes = __('*Activity time period is empty.<br>');
                $error = true; // There is an error
            }
        } else {
            $this->activity_time_periods_error_mes = __('*Activity time period is empty.<br>');
            $error = true; // There is an error
        }

        // Check activity domains
        if (isset($_POST['activity_domains'])) {
            $value = $_POST['activity_domains'];
            if (empty($value)) {
                $this->activity_domains_error_mes = __('*Activity domains is empty.<br>');
                $error = true; // There is an error
            }
        } else {
            $this->activity_domains_error_mes = __('*Activity domains is empty.<br>');
            $error = true; // There is an error
        }

        // Check entry clerk name
        if (isset($_POST['entry_clerk'])) {
            $value = $_POST['entry_clerk'];
            if (empty($value)) {
                $this->entry_clerk_error_mes = __('*Entry clerk is empty.<br>');
                $error = true; // There is an error
            }
        }

        // Ensure all expected keys exist
        if (! (isset($data['activity_name'])
            && isset($data['number_of_places'])
            && isset($data['activity_duration'])
            && isset($data['activity_date'])
            && isset($data['activity_time_periods'])
            && isset($data['activity_domains'])
            && isset($data['entry_clerk'])) ) {
            $error = true; // There is an error
        }

        // If no error, create add activity. Otherwise, echo error
        if(!$error){
            // No error
            $this->add_activity($data);
            return true;
        } else{
            // There is an error
            return false;
        }
    }


    // Function to add activity to database
    public function add_activity($data){

        // Creating all the varaibles for the SQL input
        $user_id = $this->user_id;
        $activity_name = $data['activity_name']; // ucfirst makes first letter capital.
        $number_of_participants = 0; // Number of participants is 0 when activity is created
        $number_of_places = $data['number_of_places'];
        $activity_duration = $data['activity_duration'];
        $activity_location = $data['activity_location'];
        $activity_dates = $data['activity_dates'];
        $activity_time_periods = $data['activity_time_periods'];
        $activity_domains = $data['activity_domains'];
        $entry_clerk = $data['entry_clerk'];
        $additional_notes = $data['additional_notes'];
        $registration_date = date("Y-m-d");
        $trashed = 0; // Activity is not trashed when created

        // Initialise Database object
        $DB = new Database();

        // Convert activity_dates to array
        $activity_dates_array = array_map('trim', explode(',', $activity_dates));

        // SQL query into Activities
        foreach($activity_dates_array as $activity_date){
            // SQL prepared statement into Activities
            $activity_query = "INSERT INTO Activities (user_id, activity_name, number_of_places, number_of_participants, activity_duration, activity_location, activity_date, entry_clerk, additional_notes, registration_date, trashed)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $types = "isiiisssssi"; // Types of data to be inserted
            $params = [$user_id, $activity_name, $number_of_places, $number_of_participants, $activity_duration, $activity_location, 
                        $activity_date, $entry_clerk, $additional_notes, $registration_date, $trashed]; // Parameters to be inserted

            // Send prepared statement to Database
            $DB->save_prepared($activity_query, $types, $params);

            // Set activity_id to value of primary key in Activity table
            $activity_id = $DB->last_insert_id;
            $this->activity_id = $activity_id; // Set activity_id to class variable

            // SQL query into Activity_Time_Periods
            foreach($activity_time_periods as $time_period){
                // SQL query
                $activity_time_periods_query = "INSERT INTO Activity_Time_Periods (user_id, activity_id, time_period)
                    VALUES ('$user_id', '$activity_id', '$time_period')";

                // Send data to Database
                $DB->save($activity_time_periods_query);  
            }

            // SQL query into Activity_Domains
            foreach($activity_domains as $domain){
                // SQL query
                $activity_domains_query = "INSERT INTO Activity_Domains (user_id, activity_id, domain)
                    VALUES ('$user_id', '$activity_id', '$domain')";
                
                // Send data to Database
                $DB->save($activity_domains_query);  
            }
        }
        
    }
    
}

?>