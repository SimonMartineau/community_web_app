<?php

// Class to edit volunteer data
class Edit_Volunteer{

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
    public $user_id;

    public function __construct($user_id) {
        $this->user_id = $user_id;
    }

    // Analyses data sent by user
    public function evaluate($volunteer_id, $data){

        // Initialise error contract variable
        $error = false;

        // Check first name
        if (isset($_POST['first_name'])) {
            $value = $_POST['first_name'];
            if (empty($value)) {
                $this->first_name_error_mes = __('*First name is empty.') . "<br>";
                $error = true;
            } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $value)) {
                $this->first_name_error_mes = __('*Please enter a valid first name.') . "<br>";
                $error = true;
            }
        }

        // Check last name
        if (isset($_POST['last_name'])) {
            $value = $_POST['last_name'];
            if (empty($value)) {
                $this->last_name_error_mes = __('*Last name is empty.') . "<br>";
                $error = true;
            } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $value)) {
                $this->last_name_error_mes = __('*Please enter a valid last name.') . "<br>";
                $error = true;
            }
        }

        // Check gender
        if (isset($_POST['gender'])) {
            $value = $_POST['gender'];
            if (empty($value)) {
                $this->gender_error_mes = __('*Gender is empty.') . "<br>";
                $error = true;
            }
        } else {
            $this->gender_error_mes = __('*Gender is empty.') . "<br>";
            $error = true;
        }

        // Check date of birth
        if (isset($_POST['date_of_birth'])) {
            $value = $_POST['date_of_birth'];
            if (empty($value)) {
                $this->date_of_birth_error_mes = __('*Date of birth is empty.') . "<br>";
                $error = true;
            }
        }

        // Check address
        if (isset($_POST['address'])) {
            $value = $_POST['address'];
            if (empty($value)) {
                $this->address_error_mes = __('*Address is empty.') . "<br>";
                $error = true;
            }
        }

        // Check zip code
        if (isset($_POST['zip_code'])) {
            $value = $_POST['zip_code'];
            if (empty($value)) {
                $this->zip_code_error_mes = __('*ZIP code is empty.') . "<br>";
                $error = true;
            }
        }

        // Check telephone number
        if (isset($_POST['telephone_number'])) {
            $value = $_POST['telephone_number'];
            if (empty($value)) {
                $this->telephone_number_error_mes = __('*Telephone number is empty.') . "<br>";
                $error = true;
            }
        }

        // Check email
        if (isset($_POST['email'])) {
            $value = $_POST['email'];
            if (empty($value)) {
                $this->email_error_mes = __('*Email is empty.') . "<br>";
                $error = true;
            } elseif (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $this->email_error_mes = __('*Please enter a valid email.') . "<br>";
                $error = true;
            }
        }

        // Check volunteer availability
        if (isset($_POST['volunteer_availability'])) {
            $value = $_POST['volunteer_availability'];
            if (empty($value)) {
                $this->volunteer_availability_error_mes = __('*Volunteer availability is empty.') . "<br>";
                $error = true;
            }
        } else {
            $this->volunteer_availability_error_mes = __('*Volunteer availability is empty.') . "<br>";
            $error = true;
        }

        // Check volunteer interests
        if (isset($_POST['volunteer_interests'])) {
            $value = $_POST['volunteer_interests'];
            if (empty($value)) {
                $this->volunteer_interests_error_mes = __('*Volunteer interests is empty.') . "<br>";
                $error = true;
            }
        } else {
            $this->volunteer_interests_error_mes = __('*Volunteer interests is empty.') . "<br>";
            $error = true;
        }

        // Check volunteer manager
        if (isset($_POST['volunteer_manager'])) {
            $value = $_POST['volunteer_manager'];
            if (empty($value)) {
                $this->volunteer_manager_error_mes = __('*Volunteer manager is empty.') . "<br>";
                $error = true;
            }
        }

        // Check entry clerk name
        if (isset($_POST['entry_clerk'])) {
            $value = $_POST['entry_clerk'];
            if (empty($value)) {
                $this->entry_clerk_error_mes = __('*Entry clerk is empty.') . "<br>";
                $error = true;
            }
        }

        // Ensure all expected keys exist
        if (! (isset($data['first_name']) 
            && isset($data['last_name']) 
            && isset($data['gender']) 
            && isset($data['date_of_birth']) 
            && isset($data['address']) 
            && isset($data['zip_code']) 
            && isset($data['telephone_number']) 
            && isset($data['email']) 
            && isset($data['volunteer_availability']) 
            && isset($data['volunteer_interests']) 
            && isset($data['volunteer_manager']) 
            && isset($data['entry_clerk']))) {
            $error = true; // There is an error
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


    // Function to edit volunteer in database
    public function edit_volunteer($volunteer_id, $data){

        // Creating all the varaibles for the SQL input
        $user_id = $this->user_id;
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
        $volunteer_manager = $data['volunteer_manager'];
        $entry_clerk = $data['entry_clerk'];
        $additional_notes = $data['additional_notes'];

        // Initialise Database object
        $DB = new Database();

        // SQL prepared statement into Volunteers
        $volunteers_query = "UPDATE Volunteers 
                SET first_name = ?, 
                    last_name = ?,
                    gender = ?,
                    date_of_birth = ?,
                    address = ?,
                    zip_code = ?,
                    telephone_number = ?,
                    email = ?,
                    volunteer_manager = ?,
                    entry_clerk = ?,
                    additional_notes = ?
                WHERE id = ? AND user_id = ?;";
        $types = "sssssssssssii"; // Types of data to be inserted
        $parameters = [$user_id, $first_name, $last_name, $gender, $date_of_birth, $address, $zip_code, $telephone_number, $email, $volunteer_manager, 
                        $entry_clerk, $additional_notes, $volunteer_id]; // Parameters to be inserted

        // Save data into database
        $DB->save_prepared($volunteers_query, $types, $parameters);

        // SQL query to delete data from Volunteer_Interests table
        $delete_interests_query = "DELETE FROM Volunteer_Interests WHERE volunteer_id = '$volunteer_id' AND user_id = '$user_id'";
        $DB->save($delete_interests_query);

        // SQL query into Volunteer_Interests
        foreach($volunteer_interests as $interest){
            $volunteers_interests_query = "INSERT INTO Volunteer_Interests (user_id, volunteer_id, interest)
                    VALUES ('$user_id', '$volunteer_id', '$interest')";
            $DB->save($volunteers_interests_query);
        }

        // SQL query to delete data from Volunteer_Availability table
        $delete_availability_query = "DELETE FROM Volunteer_Availability WHERE volunteer_id = '$volunteer_id' AND user_id = '$user_id'";
        $DB->save($delete_availability_query);

        // SQL query into Volunteer_Availability
        foreach($volunteer_availability as $availability){
            list($weekday, $time_period) = explode('-', $availability);
            $volunteers_availability_query = "INSERT INTO Volunteer_Availability (user_id, volunteer_id, weekday, time_period)
                    VALUES ('$user_id', '$volunteer_id', '$weekday', '$time_period')";
            $DB->save($volunteers_availability_query);
        }

    }

}

?>