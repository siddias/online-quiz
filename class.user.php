<?php

require_once 'dbconfig.php';

class USER
{
	private $conn;

	public function __construct() //Constructor
	{
		$database = new Database();
		$db = $database->dbConnection();
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->conn = $db;
    }

	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql); //prepare sql statement
		return $stmt;
	}

	public function lasdID()
	{
		$stmt = $this->conn->lastInsertId(); //row id of last row that was inserted in the database
		return $stmt;
	}

	public function register($id,$fname,$lname,$email,$pass,$type,$code)
	{
		try
		{
			$password = md5($pass); //hash the password
			$stmt = $this->conn->prepare("INSERT INTO members(id,fname,lname,email,pass,userType,tokenCode)
			                             VALUES(:id,:fname,:lname,:email,:pass,:userType,:tokenCode)");
			$stmt->bindparam(":id",$id);
			$stmt->bindparam(":fname",$fname);
			$stmt->bindparam(":lname",$lname);
			$stmt->bindparam(":email",$email);
			$stmt->bindparam(":pass",$password);
			$stmt->bindparam(":userType",$type);
			$stmt->bindparam(":tokenCode",$code);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $ex)
		{
			header("Location: index.php?err"); //redirect to index page with error as not found
			exit;
		}
	}

	public function login($email,$upass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM members WHERE email=:email_id");
			$stmt->execute(array(":email_id"=>$email)); //or bindparam can be used
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC); //create associative array

			if($stmt->rowCount() == 1) //user exists
			{
				if($userRow['verified']=='Y') //user is verified
				{
					if($userRow['pass']==md5($upass)) //hashes the password and checks for equality
					{
						$_SESSION['userSession'] = $userRow['userId']; //uid as session variable.uid is unique
						$_SESSION['LAST_ACTIVITY'] = time();
						return true;
					}
					else
					{
						header("Location: index.php?error"); //redirect to index page with error as not matched
						exit;
					}
				}
				else
				{
					header("Location: index.php?inactive"); //redirect to index page with inactive because not verified
					exit;
				}
			}
			else
			{
				header("Location: index.php?error"); //redirect to index page with error as not found
				exit;
			}
		}
		catch(PDOException $ex)
		{
			header("Location: index.php?err"); //redirect to index page with error as not found
			exit;
		}
	}

	public function logout()
	{
		unset($_SESSION['userSession']);
		session_destroy(); //end session
	}

	public function is_logged_in()
	{
		if (isset($_SESSION['userSession']) && isset($_SESSION['LAST_ACTIVITY'])){
				if(time() - $_SESSION['LAST_ACTIVITY'] <= 1800){
						$_SESSION['LAST_ACTIVITY'] = time();
						return true;
				}
				else{
					unset($_SESSION['userSession']);
					session_destroy(); //end session
					return false;
				}
		}
		else
			return false;
	}

	public function redirect($url)
	{
		header("Location: $url");
	}

	public function send_mail($email,$message,$subject)
	{
		require_once('lib/mailer/class.phpmailer.php');
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPDebug  =0;
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = "tls";
		$mail->Host       = "smtp.gmail.com";
		$mail->Port       = 587;
		$mail->AddAddress($email);
		$mail->Username="bmscequizit@gmail.com"; //determines which email id the mail is sent from
		$mail->Password="bmsce123";
		$mail->SetFrom("bmscequizit@gmail.com","Quiz-It");
		$mail->AddReplyTo("bmscequizit@gmail.com","Quiz-It");
		$mail->Subject  = $subject;
		$mail->MsgHTML($message); //construct body of message from html
		$mail->Send(); //send message
	}
}
