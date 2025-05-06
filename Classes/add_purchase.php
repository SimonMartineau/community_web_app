<?php

// Class to add purchase to database
class Add_Purchase{

    // Error messages for each form field
    public $item_names_error_mes = "";
    public $total_cost_error_mes = "";
    public $purchase_date_error_mes = "";
    public $entry_clerk_error_mes = "";
    public $user_id;

    public function __construct($user_id) {
        $this->user_id = $user_id;
    }


    // Analyses data sent by user
    public function evaluate($volunteer_id, $data){

        // Initialize error contract variable
        $error = false;

        // Check item names
        if (isset($_POST['item_names'])){
            $value = $_POST['item_names'];
            if (empty($value)){
                $this->item_names_error_mes = "*Item names is empty.<br>";
                $error = true; // There is an error
            }
        }

        // Check total cost
        if (isset($_POST['total_cost'])){
            $value = $_POST['total_cost'];
            if (empty($value)){
                $this->total_cost_error_mes = "*Total cost is empty.<br>";
                $error = true; // There is an error
            } elseif (!is_numeric($value)){
                $this->total_cost_error_mes = "*Please enter a valid number.<br>";
                $error = true; // There is an error
            } elseif ($value < 0){
                $this->total_cost_error_mes = "*Please enter a positive number.<br>";
                $error = true; // There is an error
            } elseif ($value > 1000){
                $this->total_cost_error_mes = "*Please enter a number less than 1000.<br>";
                $error = true; // There is an error
            }
        }

        // Check purchase date
        if (isset($_POST['purchase_date'])){
            $value = $_POST['purchase_date'];
            if (empty($value)){
                $this->purchase_date_error_mes = "*Purchase date is empty.<br>";
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
            $this->add_purchase($volunteer_id, $data);
            return true;
        } else{
            // There is an error
            return false;
        }
    }

    // Function to add purchase to database
    public function add_purchase($volunteer_id, $data){

        // Creating all the varaibles for the SQL input
        $user_id = $this->user_id;
        $item_names = $data['item_names'];
        $total_cost = $data['total_cost'];
        $purchase_date = $data['purchase_date'];
        $entry_clerk = $data['entry_clerk'];
        $additional_notes = $data['additional_notes'];

        // Assigning the purchase to a contract
        $contract_data_row = fetch_data_rows("SELECT * 
                                       FROM Contracts c
                                       WHERE c.user_id = '$user_id'
                                       AND c.volunteer_id = '$volunteer_id' 
                                       AND c.user_id = '$user_id'
                                       AND '$purchase_date' between c.issuance_date AND c.validity_date");

        // Check if the query returned any rows
        if (empty($contract_data_row)) {
            $contract_id = -1; // No results found, set contract_id to -1
        } else {
            $contract_id = $contract_data_row[0]['id']; // Use the first row's ID
        }

        // Initialise Database object
        $DB = new Database();

        // SQL prepared statement into Purchases
        $purchase_query = "INSERT INTO Purchases (user_id, volunteer_id, contract_id, item_names, total_cost, purchase_date, entry_clerk, additional_notes)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $types = "iiisisss";  // Types of data to be inserted
        $params = [$user_id, $volunteer_id, $contract_id, $item_names, $total_cost, $purchase_date, $entry_clerk, $additional_notes]; // Parameters to be inserted

        // Send prepared statement to Database
        $DB->save_prepared($purchase_query, $types, $params);
    }

}

?>