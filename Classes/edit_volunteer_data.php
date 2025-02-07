<?php

class Edit_Volunteer{
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
    public $organizer_name_error_mes = "";
    public $registration_date_error_mes = "";




    // Analyses data sent by user
    public function evaluate($volunteer_id, $data){

        $error = false; // Initialise error contract variable

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

        // Check organizer name
        if (isset($_POST['organizer_name'])){
            $value = $_POST['organizer_name'];
            if (empty($value)){
                $this->organizer_name_error_mes = "*Organizer Name is empty.<br>";
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
            $this->edit_volunteer($volunteer_id, $data);
            return true;
        } else{
            // There is an error
            return false;
        }
    }


    public function edit_volunteer($volunteer_id, $data){
        // Creating all the varaibles for the SQL input
        $first_name = ucfirst($data['first_name']);
        $last_name = ucfirst($data['last_name']);
        $gender = $data['gender'];
        $date_of_birth = $data['date_of_birth'];
        $address = $data['address'];
        $zip_code = $data['zip_code'];
        $telephone_number = $data['telephone_number'];
        $email = $data['email'];
        $volunteer_availability = $data['volunteer_availability'];
        $volunteer_interests = $data['volunteer_interests'];
        $organizer_name = $data['organizer_name'];
        $additional_notes = $data['additional_notes'];
        $registration_date = $data['registration_date'];

        // Initialise Database object
        $DB = new Database();

        // SQL query into Volunteers
        $volunteers_query = "UPDATE Volunteers 
                  SET first_name = '$first_name', 
                      last_name = '$last_name', 
                      gender = '$gender', 
                      date_of_birth = '$date_of_birth', 
                      address = '$address', 
                      zip_code = '$zip_code', 
                      telephone_number = '$telephone_number', 
                      email = '$email', 
                      organizer_name = '$organizer_name', 
                      additional_notes = '$additional_notes', 
                      registration_date = '$registration_date'
                  WHERE id = '$volunteer_id';";
        $DB->update($volunteers_query);

        // SQL query to delete data from Volunteer_Interests table
        $delete_interests_query = "DELETE FROM Volunteer_Interests WHERE volunteer_id = '$volunteer_id'";
        $DB->update($delete_interests_query);

        // SQL query into Volunteer_Interests
        foreach($volunteer_interests as $interest){
            $volunteers_interests_query = "insert into Volunteer_Interests (volunteer_id, interest)
                    values ('$volunteer_id', '$interest')";
            $DB->save($volunteers_interests_query);
        }

        // SQL query to delete data from Volunteer_Availability table
        $delete_availability_query = "DELETE FROM Volunteer_Availability WHERE volunteer_id = '$volunteer_id'";
        $DB->update($delete_availability_query);

        // SQL query into Volunteer_Availability
        foreach($volunteer_availability as $availability){
            list($weekday, $time_period) = explode('-', $availability);
            $volunteers_availability_query = "insert into Volunteer_Availability (volunteer_id, weekday, time_period)
            values ('$volunteer_id', '$weekday', '$time_period')";
            $DB->save($volunteers_availability_query);
        }

    }

    
}

?>