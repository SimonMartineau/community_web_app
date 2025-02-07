<?php

class Add_Activity{
    public $activity_name_error_mes = "";
    public $number_of_places_error_mes = "";
    public $activity_duration_error_mes = "";
    public $activity_dates_error_mes = "";
    public $activity_time_periods_error_mes = "";
    public $activity_domains_error_mes = "";
    public $organizer_name_error_mes = "";


    // Analyses data sent by user
    public function evaluate($data){

        $error = false; // Initialise error contract variable

        // Check activity name
        if (isset($_POST['activity_name'])){
            $value = $_POST['activity_name'];
            if (empty($value)){
                $this->activity_name_error_mes = "*Activity name is empty.<br>";
                $error = true; // There is an error
            }
        }

        // Check activity number of participants
        if (isset($_POST['number_of_places'])){
            $value = $_POST['number_of_places'];
            if (empty($value)){
                $this->number_of_places_error_mes = "*Number of participants is empty.<br>";
                $error = true; // There is an error
            } elseif (!preg_match("/^[0-9]*$/",$value)){
                $this->number_of_places_error_mes = "*Please enter a number.<br>";
                $error = true; // There is an error
            }
        }

        // Check activity duration
        if (isset($_POST['activity_duration'])){
            $value = $_POST['activity_duration'];
            if (empty($value)){
                $this->activity_duration_error_mes = "*Activity duration is empty.<br>";
                $error = true; // There is an error
            } elseif (!preg_match("/^[0-9]*$/",$value)){
                $this->activity_duration_error_mes = "*Please enter a number.<br>";
                $error = true; // There is an error
            }
        }

        // Check activity date
        if (isset($_POST['activity_dates'])){
            $value = $_POST['activity_dates'];
            if (empty($value)){
                $this->activity_dates_error_mes = "*Activity date is empty.<br>";
                $error = true; // There is an error
            }
        }

        // Check activity time period
        if (isset($_POST['activity_time_periods'])){
            $value = $_POST['activity_time_periods'];
            if (empty($value)){
                $this->activity_time_periods_error_mes = "*Activity time period is empty.<br>";
                $error = true; // There is an error
            } 
        } else{
            $this->activity_time_periods_error_mes = "*Activity time period is empty.<br>";
            $error = true; // There is an error
        }

        // Check activity domains
        if (isset($_POST['activity_domains'])){
            $value = $_POST['activity_domains'];
            if (empty($value)){
                $this->activity_domains_error_mes = "*Activity domains is empty.<br>";
                $error = true; // There is an error
            }
        } else{
            $this->activity_domains_error_mes = "*Activity domains is empty.<br>";
                $error = true; // There is an error
        }

        // Check organizer name
        if (isset($_POST['organizer_name'])){
            $value = $_POST['organizer_name'];
            if (empty($value)){
                $this->organizer_name_error_mes = "*Organizer name is empty.<br>";
                $error = true; // There is an error
            }
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


    public function add_activity($data){
        // Creating all the varaibles for the SQL input
        $activity_name = $data['activity_name']; // ucfirst makes first letter capital.
        $number_of_participants = 0;
        $number_of_places = $data['number_of_places'];
        $activity_duration = $data['activity_duration'];
        $activity_location = $data['activity_location'];
        $activity_dates = $data['activity_dates'];
        $activity_time_periods = $data['activity_time_periods'];
        $activity_domains = $data['activity_domains'];
        $organizer_name = $data['organizer_name'];
        $additional_notes = $data['additional_notes'];
        $registration_date = date("Y-m-d");
        $trashed = 0;

        // Initialise Database object
        $DB = new Database();

        $activity_dates_array = array_map('trim', explode(',', $activity_dates));
        // SQL query into Activities
        foreach($activity_dates_array as $activity_date){
            $activity_query = "insert into Activities (activity_name, number_of_places, number_of_participants, activity_duration, activity_location, activity_date, organizer_name, additional_notes, registration_date, trashed)
                    values ('$activity_name', '$number_of_places', '$number_of_participants', '$activity_duration', '$activity_location', '$activity_date', '$organizer_name', '$additional_notes', '$registration_date', '$trashed')";
                
            // Send data to db
            $DB->save($activity_query);

            // Set activity_id to value of primary key in Activity table
            $activity_id = $DB->last_insert_id;


            // SQL query into Activity_Time_Periods
            foreach($activity_time_periods as $time_period){
                $activity_time_periods_query = "insert into Activity_Time_Periods (activity_id, time_period)
                values ('$activity_id', '$time_period')";

                // Send data to db
                $DB->save($activity_time_periods_query);  
            }

            // SQL query into Activity_Domains
            foreach($activity_domains as $domain){
                $activity_domains_query = "insert into Activity_Domains (activity_id, domain)
                    values ('$activity_id', '$domain')";
                
                // Send data to db
                $DB->save($activity_domains_query);  
            }
        }
        
    }

    
}

?>