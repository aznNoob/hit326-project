<?php
/*
* PDO Database Class
* Connect to database
* Create prepared statements
* Bind values
* Return rows and results
*/

class Database
{
    // Properties needed for database connection (Constant values in app/config/config.php)
    private $host = DB_HOST;
    private $username = DB_USERNAME;
    private $password = DB_PASSWORD;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $errors;

    public function __construct()
    {
        // Set DSN
        $dsn = "mysql:host=$this->host;dbname=$this->dbname";
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        // Create PDO Instance
        try {
            $this->dbh = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            $this->errors = $e->getMessage();
            echo $this->errors;
        }
    }

    // Prepare statement with query
    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($parameters, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
        }

        $this->stmt->bindValue($parameters, $value, $type);
    }

    // Execute prepared statement
    public function execute()
    {
        return $this->stmt->execute();
    }

    // Get the ID of last insert object 
    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    // Get result set as an array of objects
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get single object record as object
    public function resultSingle()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Get row count
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }
}
