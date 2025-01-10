<?php

class Add_Volunteer{
    public $first_name_error_mes = "";
    public $last_name_error_mes = "";
    public $gender_error_mes = "";
    public $date_of_birth_error_mes = "";
    public $address_error_mes = "";
    public $zip_code_error_mes = "";
    public $telephone_number_error_mes = "";
    public $email_error_mes = "";
    public $volunteer_availability_error_mes = "";
    public $volunteer_interests_error_mes = "";
    public $registration_supervisor_error_mes = "";
    public $assigned_area_error_mes = "";



    // Analyses data sent by user
    public function evaluate($data){

        $error = false; // Initialise error check variable

        // Check first name
        if (isset($_POST['first_name'])){
            $value = $_POST['first_name'];
            if (empty($value)){
                $this->first_name_error_mes = "*First name is empty.<br>";
                $error = true; // There is an error
            } elseif (!preg_match("/^[a-zA-Z-' ]*$/",$value)){
                $this->first_name_error_mes = "*Please enter a valid first name.<br>";
                $error = true; // There is an error
            }
        }

        // Check last name
        if (isset($_POST['last_name'])){
            $value = $_POST['last_name'];
            if (empty($value)){
                $this->last_name_error_mes = "*Last name is empty.<br>";
                $error = true; // There is an error
            } elseif (!preg_match("/^[a-zA-Z-' ]*$/",$value)){
                $this->last_name_error_mes = "*Please enter a valid last name.<br>";
                $error = true; // There is an error
            }
        }

        // Check gender
        if (isset($_POST['gender'])){
            $value = $_POST['gender'];
            if (empty($value)){
                $this->gender_error_mes = "*Gender is empty.<br>";
                $error = true; // There is an error
            } 
        } else{
            $this->gender_error_mes = "*Gender is empty.<br>";
            $error = true; // There is an error
        }

        // Check date of birth
        if (isset($_POST['date_of_birth'])){
            $value = $_POST['date_of_birth'];
            if (empty($value)){
                $this->date_of_birth_error_mes = "*Date of birth is empty.<br>";
                $error = true; // There is an error
            }
        }

        // Check address
        if (isset($_POST['address'])){
            $value = $_POST['address'];
            if (empty($value)){
                $this->address_error_mes = "*Address is empty.<br>";
                $error = true; // There is an error
            }
        }

        // Check zip code
        if (isset($_POST['zip_code'])){
            $value = $_POST['zip_code'];
            if (empty($value)){
                $this->zip_code_error_mes = "*ZIP code is empty.<br>";
                $error = true; // There is an error
            }
        }

        // Check telephone number
        if (isset($_POST['telephone_number'])){
            $value = $_POST['telephone_number'];
            if (empty($value)){
                $this->telephone_number_error_mes = "*Telephone number is empty.<br>";
                $error = true; // There is an error
            }
        }

        // Check email
        if (isset($_POST['email'])){
            $value = $_POST['email'];
            if (empty($value)){
                $this->email_error_mes = "*Email is empty.<br>";
                $error = true; // There is an error
            } elseif (!filter_var($value, FILTER_VALIDATE_EMAIL)){
                $this->email_error_mes = "*Please enter a valid email.<br>";
                $error = true; // There is an error
            }
        }

        // Check volunteer availability
        if (isset($_POST['volunteer_availability'])){
            $value = $_POST['volunteer_availability'];
            if (empty($value)){
                $this->volunteer_availability_error_mes = "*Volunteer availability is empty.<br>";
                $error = true; // There is an error
            }
        } else{
            $this->volunteer_availability_error_mes = "*Volunteer availability is empty.<br>";
                $error = true; // There is an error
        }

        // Check volunteer interests
        if (isset($_POST['volunteer_interests'])){
            $value = $_POST['volunteer_interests'];
            if (empty($value)){
                $this->volunteer_interests_error_mes = "*Volunteer interests is empty.<br>";
                $error = true; // There is an error
            }
        } else{
            $this->volunteer_interests_error_mes = "*Volunteer interests is empty.<br>";
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

        // If no error, create add volunteer. Otherwise, echo error
        if(!$error){
            // No error
            $this->add_volunteer($data);
            return true;
        } else{
            // There is an error
            return false;
        }
    }


    public function add_volunteer($data){
        // Creating all the varaibles for the SQL input
        $first_name = ucfirst($data['first_name']); // ucfirst makes first letter capital.
        $last_name = ucfirst($data['last_name']);
        $gender = $data['gender'];
        $date_of_birth = $data['date_of_birth'];
        $address = $data['address'];
        $zip_code = $data['zip_code'];
        $telephone_number = $data['telephone_number'];
        $email = $data['email'];
        $points = 0;
        $hours_worked = 0;
        $volunteer_availability = $data['volunteer_availability'];
        $volunteer_interests = $data['volunteer_interests'];
        $other_interest = $data['volunteer_interests'];
        $registration_supervisor = $data['registration_supervisor'];
        $assigned_area = $data['assigned_area'];
        $additional_notes = $data['additional_notes'];
        $registration_date = date("Y-m-d");


        // SQL query into Members
        $members_query = "insert into Members (first_name, last_name, gender, date_of_birth, address, zip_code, telephone_number, email, points, hours_worked, assigned_area, organizer_name, notes, registration_date)
                  values ('$first_name', '$last_name', '$gender', '$date_of_birth', '$address', '$zip_code', '$telephone_number', '$email', '$points', '$hours_worked', '$assigned_area', '$registration_supervisor', '$additional_notes', '$registration_date')";
            
        // SQL query into Member_Availability
        //$member_availability_query = "insert into members (member_id, weekday, time_period)
         //         values ()";
                
        // SQL query into Member_Interests
        //$members_interests_query = "insert into members (member_id, interest)
        //          values ()";
                
        // Send data to db
        $DB = new Database();
        $DB->save($members_query);
        //$DB->save($member_availability_query);
        //$DB->save($members_interests_query);
    }

    
}

?>