<?php

class Add_Social_Activity{
    public $activity_name_error_mes = "";
    public $activity_duration_error_mes = "";
    public $activity_time_period_error_mes = "";
    public $activity_domains_error_mes = "";
    public $registration_supervisor_error_mes = "";
    public $assigned_area_error_mes = "";



    // Analyses data sent by user
    public function evaluate($data){

        $error = false; // Initialise error check variable

        // Check activity name
        if (isset($_POST['activity_name'])){
            $value = $_POST['activity_name'];
            if (empty($value)){
                $this->activity_name_error_mes = "*Activity name is empty.<br>";
                $error = true; // There is an error
            } elseif (!preg_match("/^[a-zA-Z-' ]*$/",$value)){
                $this->activity_name_error_mes = "*Please enter a valid activity name.<br>";
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

        // Check activity time period
        if (isset($_POST['activity_time_period'])){
            $value = $_POST['activity_time_period'];
            if (empty($value)){
                $this->activity_time_period_error_mes = "*Activity time period is empty.<br>";
                $error = true; // There is an error
            } 
        } else{
            $this->activity_time_period_error_mes = "*Activity time period is empty.<br>";
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

        // Check registration supervisor
        if (isset($_POST['registration_supervisor'])){
            $value = $_POST['registration_supervisor'];
            if (empty($value)){
                $this->registration_supervisor_error_mes = "*Registration supervisor is empty.<br>";
                $error = true; // There is an error
            }
        }

        // Check assigned area
        if (isset($_POST['assigned_area'])){
            $value = $_POST['assigned_area'];
            if (empty($value)){
                $this->assigned_area_error_mes = "*Assigned area is empty.<br>";
                $error = true; // There is an error
            }
        }

        // If no error, create add social activity. Otherwise, echo error
        if(!$error){
            // No error
            $this->add_social_activity($data);
            return true;
        } else{
            // There is an error
            return false;
        }
    }


    public function add_social_activity($data){
        // Creating all the varaibles for the SQL input
        $activity_name = $data['first_name']; // ucfirst makes first letter capital.
        $activity_duration = $data['last_name'];
        $activity_time_period = $data['activity_time_period'];
        $activity_domains = $data['activity_domains'];
        $registration_supervisor = $data['registration_supervisor'];
        $assigned_area = $data['assigned_area'];
        $additional_notes = $data['additional_notes'];
        $registration_date = date("Y-m-d");


        // SQL query into Members
        $activity_query = "insert into Activities ()
                values ()";
                
        // Send data to db
        $DB = new Database();
        $DB->save($activity_query);
        //$DB->save($member_availability_query);
        //$DB->save($members_interests_query);
    }

    
}

?>