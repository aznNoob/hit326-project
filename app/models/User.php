<?php

class User
{
    private $db;

    public function __construct()
    {
        // Instantiate the database base model to get needed properties and methods
        $this->db = new Database;
    }

    // Insert the register data into the database
    public function register($data)
    {
        $this->db->query('INSERT INTO users(name, email, password) VALUES(:name, :email, :password)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //Gets login parameters from controller, checks if email/password matches and if correct, grabs user data
    public function login($email, $password)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $userRow = $this->db->resultSingle();
        $hashed_password = $userRow->password;
        if (password_verify($password, $hashed_password)) {
            return $userRow;
        } else {
            return false;
        }
    }

    // See if email is existing already in database
    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * from users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->resultSingle();
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
