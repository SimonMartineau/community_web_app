<?php

class Add_Purchase{
    public $item_names_error_mes = "";
    public $total_cost_error_mes = "";
    public $purchase_date_error_mes = "";
    public $entry_clerk_error_mes = "";



    // Analyses data sent by user
    public function evaluate($volunteer_id, $data){

        $error = false; // Initialize error contract variable

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


    public function add_purchase($volunteer_id, $data){
        // Creating all the varaibles for the SQL input
        $item_names = $data['item_names'];
        $total_cost = $data['total_cost'];
        $purchase_date = $data['purchase_date'];
        $entry_clerk = $data['entry_clerk'];
        $additional_notes = $data['additional_notes'];

        // Assigning the purchase to a contract
        $contract_data_row = fetch_data("SELECT * 
                                       FROM Contracts c
                                       WHERE c.volunteer_id = '$volunteer_id' 
                                       AND '$purchase_date' between c.issuance_date AND c.validity_date");

        // Check if the query returned any rows
        if (empty($contract_data_row)) {
            $contract_id = -1; // No results found, set contract_id to -1
        } else {
            $contract_id = $contract_data_row[0]['id']; // Use the first row's ID
        }

        // Initialise Database object
        $DB = new Database();

        // SQL query into Purchases
        $purchase_query = "insert into Purchases (volunteer_id, contract_id, item_names, total_cost, purchase_date, entry_clerk, additional_notes)
                  values ('$volunteer_id', '$contract_id', '$item_names', '$total_cost', '$purchase_date', '$entry_clerk', '$additional_notes')";
        $DB->save($purchase_query);
    }

    
}

?>