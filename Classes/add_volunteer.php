<?php

// Class to add volunteer to database
class Add_Volunteer{

    // Error messages for each form field
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
    public $volunteer_manager_error_mes = "";
    public $entry_clerk_error_mes = "";
    public $volunteer_id = "";


    // Analyses data sent by user
    public function evaluate($data){

        // Initialize error contract variable
        $error = false;

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

        // Check volunteer manager
        if (isset($_POST['volunteer_manager'])){
            $value = $_POST['volunteer_manager'];
            if (empty($value)){
                $this->volunteer_manager_error_mes = "*Volunteer manager is empty.<br>";
                $error = true; // There is an error
            }
        }

        // Check entry clerk name
        if (isset($_POST['entry_clerk'])){
            $value = $_POST['entry_clerk'];
            if (empty($value)){
                $this->entry_clerk_error_mes = "*Entry clerk is empty.<br>";
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


    // Function to add volunteer to database
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
        $hours_required = 0;
        $hours_completed = 0;
        $volunteer_availability = $data['volunteer_availability'];
        $volunteer_interests = $data['volunteer_interests'];
        $volunteer_manager = $data['volunteer_manager'];
        $entry_clerk = $data['entry_clerk'];
        $additional_notes = $data['additional_notes'];
        $registration_date = date("Y-m-d");
        $trashed = 0;  // By default the volunteer isn't trashed

        // Initialise Database object
        $DB = new Database();

        // SQL prepared statement into Volunteers
        $volunteer_query = "INSERT INTO Volunteers (first_name, last_name, gender, date_of_birth, address, zip_code, telephone_number, email, points, hours_required, hours_completed, volunteer_manager, entry_clerk, additional_notes, registration_date, trashed)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $types = "ssssssssiiissssi"; // Types of data to be inserted
        $params = [$first_name, $last_name, $gender, $date_of_birth, $address, $zip_code, $telephone_number, $email, $points, $hours_required, $hours_completed, 
                    $volunteer_manager, $entry_clerk, $additional_notes, $registration_date, $trashed]; // Parameters to be inserted

        // Send prepared statement to Database
        $DB->save_prepared($volunteer_query, $types, $params);
        
        // Set volunteer_id to value of primary key in Volunteers table
        $volunteer_id = $DB->last_insert_id;
        $this->volunteer_id = $volunteer_id;

        // SQL query into Volunteer_Availability
        foreach($volunteer_availability as $availability){
            list($weekday, $time_period) = explode('-', $availability);
            $volunteers_availability_query = "INSERT INTO Volunteer_Availability (volunteer_id, weekday, time_period)
                VALUES ('$volunteer_id', '$weekday', '$time_period')";
            $DB->save($volunteers_availability_query);
        }

        // SQL query into Volunteer_Interests
        foreach($volunteer_interests as $interest){
            $volunteers_interests_query = "INSERT INTO Volunteer_Interests (volunteer_id, interest)
                VALUES ('$volunteer_id', '$interest')";
            $DB->save($volunteers_interests_query);
        }
    }
    
}

?>