<?php

class CSRF {

    protected $db;

    public function __construct()
    {
        $instance = ConnectDb::getInstance();
        $this->db = $instance->getConnection();
    }

    /**
     * Generate a token for CSRF validation and store it in the database
     */
    public function generateToken()
    {
        $token = bin2hex(random_bytes(50));
        
        $sql = "INSERT INTO csrf_tokens (ip_address, token) VALUES (:ip_address, :token)";

        $query = $this->db->prepare($sql);
        $query->bindValue('ip_address', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $query->bindValue('token', md5($token), PDO::PARAM_STR);
        $query->execute();

        return $token;
    }

    /**
     * Check whether the passed $token is valid
     */
    public function validToken($token)
    {
        $sql = "
            SELECT id 
            FROM csrf_tokens 
            WHERE ip_address = :ip_address 
                AND token = :token
                AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
        ";

        $query = $this->db->prepare($sql);
        $query->bindValue('ip_address', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
        $query->bindValue('token', md5($token), PDO::PARAM_STR);
        $query->execute();

        return $query->rowCount() === 1;
    }

    /**
     * This would be run daily using a cron job to clean out the database of old tokens
     */
    public function deleteOldTokens()
    {
        $sql = "DELETE FROM csrf_tokens WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        $query = $this->db->prepare($sql);
        $query->execute();
    }

}