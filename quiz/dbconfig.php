<?php
class Database
{

    private $host = "localhost";    //hostname
    private $db_name = "dbtest";    //database name
    private $username = "root";     //username for database
    private $password = "";         //password for database
    public $conn;

    public function dbConnection()
	{

	    $this->conn = null;
        try
		{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	//throws an Exception if cannot connect
        }
		catch(PDOException $exception)
		{
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
