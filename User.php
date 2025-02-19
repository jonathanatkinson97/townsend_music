<?php

class User
{
    protected $db;
    protected $id;

    public function __construct()
    {
        $instance = ConnectDb::getInstance();
        $this->db = $instance->getConnection();
    }

    /**
     * Store the user in the database if they do not already exist
     */
    public function createIfNotExists($request) 
    {
        if(!$this->userExists($request['email'])) {

            $sql = "
                INSERT INTO users (name, phone, email, subscribed) 
                VALUES (:name, :phone, :email, :subscribed)
            ";

            $query = $this->db->prepare($sql);

            $query->bindValue('name', $request['name'], PDO::PARAM_STR);
            $query->bindValue('phone', $request['phone'], PDO::PARAM_STR);
            $query->bindValue('email', $request['email'], PDO::PARAM_STR);
            $query->bindValue('subscribed', $request['subscribed'] ?? false, PDO::PARAM_BOOL);

            $query->execute();
            $this->id = $this->db->lastInsertId();

        } else {
            
            $sql = "SELECT id FROM users WHERE email = :email";

            $query = $this->db->prepare($sql);
            $query->bindValue('email', $request['email'], PDO::PARAM_STR);
            $query->execute();

            $result = $query->fetchAll(PDO::FETCH_ASSOC)[0];

            $this->id = $result['id'];
        }
    }


    /**
     * Detect whether or not a user is already in our database
     */
    protected function userExists(string $email): bool
    {
        $sql = "SELECT id FROM users WHERE email = :email";

        $query = $this->db->prepare($sql);
        $query->bindParam('email', $email, PDO::PARAM_STR);
        $query->execute();

        return ($query->rowCount() > 0);
    }

    /**
     * Return the user's ID
     */
    public function getId() 
    {
        return $this->id;
    }

}