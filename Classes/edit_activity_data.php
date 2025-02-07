<?php

class Edit_Activity{
    public $activity_name_error_mes = "";
    public $number_of_places_error_mes = "";
    public $activity_duration_error_mes = "";
    public $activity_date_error_mes = "";
    public $activity_time_periods_error_mes = "";
    public $activity_domains_error_mes = "";
    public $organizer_name_error_mes = "";
    public $registration_date_error_mes = "";


    // Analyses data sent by user
    public function evaluate($activity_id, $data){

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
        if (isset($_POST['activity_date'])){
            $value = $_POST['activity_date'];
            if (empty($value)){
                $this->activity_date_error_mes = "*Activity date is empty.<br>";
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

        // Check registration date completed
        if (isset($_POST['registration_date'])){
            $value = $_POST['registration_date'];
            if (empty($value)){
                $this->registration_date_error_mes = "*Registration date is empty.<br>";
                $error = true; // There is an error
            }
        }

        // If no error, create add volunteer. Otherwise, echo error
        if(!$error){
            // No error
            $this->edit_activity($activity_id, $data);
            return true;
        } else{
            // There is an error
            return false;
        }
    }


    public function edit_activity($activity_id, $data){
        // Creating all the varaibles for the SQL input
        $activity_name = $data['activity_name']; // ucfirst makes first letter capital.
        $number_of_places = $data['number_of_places'];
        $activity_duration = $data['activity_duration'];
        $activity_location = $data['activity_location'];
        $activity_date = $data['activity_date'];
        $activity_time_periods = $data['activity_time_periods'];
        $activity_domains = $data['activity_domains'];
        $organizer_name = $data['organizer_name'];
        $additional_notes = $data['additional_notes'];
        $registration_date = date("Y-m-d");

        // Initialise Database object
        $DB = new Database();

        // SQL query into Activities
        $activity_query = "UPDATE Activities 
                  SET activity_name = '$activity_name', 
                      number_of_places = '$number_of_places', 
                      activity_duration = '$activity_duration', 
                      activity_location = '$activity_location', 
                      activity_date = '$activity_date', 
                      organizer_name = '$organizer_name',
                      additional_notes = '$additional_notes',
                      registration_date = '$registration_date'
                  WHERE id = '$activity_id';";
        $DB->update($activity_query);

        // SQL query to delete data from Activity_Time_Periods table
        $delete_activiy_time_periods_query = "DELETE FROM Activity_Time_Periods WHERE activity_id = '$activity_id'";
        $DB->update($delete_activiy_time_periods_query);

        // SQL query into Activity_Time_Periods
        foreach($activity_time_periods as $time_period){
            $activity_time_periods_query = "insert into Activity_Time_Periods (activity_id, time_period)
            values ('$activity_id', '$time_period')";

            // Send data to db
            $DB->save($activity_time_periods_query);  
        }

        // SQL query to delete data from Activity_Domains table
        $delete_activity_domains_query = "DELETE FROM Activity_Domains WHERE activity_id = '$activity_id'";
        $DB->update($delete_activity_domains_query);

        // SQL query into Activity_Domains
        foreach($activity_domains as $domain){
            $activity_domains_query = "insert into Activity_Domains (activity_id, domain)
                values ('$activity_id', '$domain')";
            
            // Send data to db
            $DB->save($activity_domains_query);  
        }

    }
}

?>