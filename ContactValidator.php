<?php

class ContactValidator {

    protected $data;
    protected $db;

    protected $required_fields = ['name', 'email', 'phone', 'message'];

    public function __construct(array $data) 
    {
        $this->data = $data;
        
        $instance = ConnectDb::getInstance();
        $this->db = $instance->getConnection();
    }

    /**
     * Run form validation
     */
    public function validate() 
    {
        $errors = [];

        foreach($this->data as $key => $value) {

            if(method_exists('ContactValidator', $key)) {
                $value = $this->$key($value);
            }

            if(is_array($value) && isset($value['error'])) {
                
                $errors[$key] = $value['error'];

            } else {

                $this->data[$key] = $value;

            }
        }

        return $errors;
    }

    /********************
     * VALIDATION RULES *
     ********************/

    protected function email($email)
    {
        if(!is_string($email) || strlen($email) < 1) {
            return ['error' => "The Email field is required"];
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['error' => "Please enter a valid email address"];
        }

        return $email;
    }

    protected function message($message) {
        if(!is_string($message) || strlen($message) < 25){
            return ['error' => "Please enter a message of at least 25 characters"];
        }

        return $message;
    }

     protected function name($name)
     {
         if(!is_string($name) || strlen($name) < 1) {
             return ['error' => "The Name field is required"];
         }

         return $name;
     }

     protected function phone($phone) {

        preg_replace('/[^0-9]/', '', $phone);

        if(!is_string($phone) || strlen($phone) < 10){
            return ['error' => "Please enter a valid phone number"];
        }

        return $phone;
    }

}