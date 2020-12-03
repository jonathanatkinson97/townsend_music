<?php

class Message {

    protected $db;
    protected $id;
    protected $mail_to = 'jonathan.atkinson97@gmail.com';

    public function __construct()
    {
        $instance = ConnectDb::getInstance();
        $this->db = $instance->getConnection();
    }

    public function create($request, $user_id)
    {
        $this->message = $request['message'];

        $sql = "
                INSERT INTO messages (user_id, message, ip_address) 
                VALUES (:user_id, :message, :ip_address)
            ";

        $query = $this->db->prepare($sql);

        $query->bindParam('user_id', $user_id, PDO::PARAM_INT);
        $query->bindValue('message', $request['message'], PDO::PARAM_STR);
        $query->bindValue('ip_address', $_SERVER['REMOTE_ADDR'], PDO::PARAM_BOOL);

        $query->execute();
        $this->id = $this->db->lastInsertId();
    }

    public function getId() 
    {
        return $this->id;
    }

    public function mail()
    {
        $subject = "New Message from User";
        $user = $this->getUser();

        $body = "
            New message received from " . $user['name'] . ": 
            <br />
            '" . $this->message . "'
            <br />
            User details: email: " . $user['email'] . ", phone: " . $user['phone'];

        mail($this->mail_to, $subject, $body);
    }


    protected function getUser() 
    {
        $sql = "
            SELECT u.* 
            FROM users u
                JOIN messages m ON m.user_id = u.id
            WHERE m.id = :id 
            LIMIT 1
        ";

        $query = $this->db->prepare($sql);
        $query->bindParam('id', $this->id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC)[0];
    }


}