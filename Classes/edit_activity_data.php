<?php

// Class to edit activity in database
class Edit_Activity{

    // Error messages for each form field
    public $activity_name_error_mes = "";
    public $number_of_places_error_mes = "";
    public $activity_duration_error_mes = "";
    public $activity_date_error_mes = "";
    public $activity_time_periods_error_mes = "";
    public $activity_domains_error_mes = "";
    public $entry_clerk_error_mes = "";
    public $registration_date_error_mes = "";


    // Analyses data sent by user
    public function evaluate($activity_id, $data){

        // Initialise error contract variable
        $error = false;

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
            } elseif ($value < 0){
                $this->number_of_places_error_mes = "*Please enter a positive number.<br>";
                $error = true; // There is an error
            } elseif ($value > 1000){
                $this->number_of_places_error_mes = "*Please enter a number less than 1000.<br>";
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
            } elseif ($value < 0){
                $this->activity_duration_error_mes = "*Please enter a positive number.<br>";
                $error = true; // There is an error
            } elseif ($value > 1000){
                $this->activity_duration_error_mes = "*Please enter a number less than 1000.<br>";
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

        // Check entry clerk name
        if (isset($_POST['entry_clerk'])){
            $value = $_POST['entry_clerk'];
            if (empty($value)){
                $this->entry_clerk_error_mes = "*Entry clerk is empty.<br>";
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


    // Function to edit activity in database
    public function edit_activity($activity_id, $data){
        
        // Creating all the varaibles for the SQL input
        $activity_name = $data['activity_name']; // ucfirst makes first letter capital.
        $number_of_places = $data['number_of_places'];
        $activity_duration = $data['activity_duration'];
        $activity_location = $data['activity_location'];
        $activity_date = $data['activity_date'];
        $activity_time_periods = $data['activity_time_periods'];
        $activity_domains = $data['activity_domains'];
        $entry_clerk = $data['entry_clerk'];
        $additional_notes = $data['additional_notes'];
        $registration_date = date("Y-m-d");

        // Initialise Database object
        $DB = new Database();

        // SQL prepared statement into Activities
        $actvity_query = "UPDATE Activities
                    SET activity_name = ?,
                        number_of_places = ?,
                        activity_duration = ?,
                        activity_location = ?,
                        activity_date = ?,
                        entry_clerk = ?,
                        additional_notes = ?,
                        registration_date = ?
                    WHERE id = ?";
        $types = "siisssssi"; // Types of data to be inserted
        $params = array($activity_name, $number_of_places, $activity_duration, $activity_location, $activity_date, 
                        $entry_clerk, $additional_notes, $registration_date, $activity_id); // Parameters to be inserted
        
        // Send prepared statement to Database
        $DB->save_prepared($actvity_query, $types, $params);

        // SQL query to delete data from Activity_Time_Periods table
        $delete_activiy_time_periods_query = "DELETE FROM Activity_Time_Periods WHERE activity_id = '$activity_id'";
        $DB->save($delete_activiy_time_periods_query);

        // SQL query into Activity_Time_Periods
        foreach($activity_time_periods as $time_period){
            $activity_time_periods_query = "INSERT INTO Activity_Time_Periods (activity_id, time_period)
                VALUES ('$activity_id', '$time_period')";

            // Send data to db
            $DB->save($activity_time_periods_query);  
        }

        // SQL query to delete data from Activity_Domains table
        $delete_activity_domains_query = "DELETE FROM Activity_Domains WHERE activity_id = '$activity_id'";
        $DB->save($delete_activity_domains_query);

        // SQL query into Activity_Domains
        foreach($activity_domains as $domain){
            $activity_domains_query = "INSERT INTO Activity_Domains (activity_id, domain)
                VALUES ('$activity_id', '$domain')";
            
            // Send data to db
            $DB->save($activity_domains_query);  
        }

    }
}

?>