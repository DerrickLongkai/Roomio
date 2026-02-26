<?php
// Enable error reporting while developing
ini_set('display_errors', 1);
error_reporting(E_ALL);

//start session
session_start();

// DB credentials — change these
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dzt_db"; // change to your DB name

// Connect (OOP mysqli)
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

// Get the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_POST['date']) && !empty($_POST['date']) && isset($_POST['comment']) && !empty($_POST['comment'])){

        //GET tenant email
        $email = $_SESSION["Tenantemail"];

        $date = pass_input($_POST['date']);
        $comment = pass_input($_POST['comment']);

        $stmt=$conn->prepare("INSERT INTO testimonial(Tenant,Date,Comment) VALUES(?,?,?)");
        $stmt->bind_param("sss",$email,$date,$comment);
        $result=$stmt->execute();
        if($result){   
               
            header("Location: Tenant.php?error=uploaded");
            exit(); 
        }
        else {
            echo "Error sign up: " . $stmt->error;
        }
        $stmt->close();


    }
    else{
        header("Location: Tenant.php?error=fields_empty");
        exit();
                
    }
}

 //function pass_input() to sanitize and validate the XSS attacks
 function pass_input($data){
    $data = trim($data);// trim remove unneeded space
    $data = stripcslashes($data);
    $data = strip_tags($data);// remove html elment
    return $data;// return validated data value
}
?>