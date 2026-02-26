<?php

// database connection
$con = mysqli_connect("localhost", "root", "", "dzt_db");

if (!$con) {
    echo "Connection failed ", mysqli_connect_error();
}

// Get the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // First, we check if they are setted and not empty
    // if is set and not empty
    if(isset($_POST["email"]) && !empty($_POST["email"]) && isset($_POST["password"]) && !empty($_POST["password"])
     && isset($_POST["role"]) && !empty($_POST["role"])){

        $email = pass_input($_POST["email"]);
        $password = pass_input($_POST["password"]);
        $role = pass_input($_POST['role']);
        // Sanitize input
        $role = htmlspecialchars($role);

        // then check which role is selected
        if($role === "tenant"){
        
            // check if the user is existed in our database or not
            // by using php prepared statement logic to keep safe and avoid users' attack
            // check inputed name with email and password in our table
            $stmt=$con->prepare("SELECT FirstName,Email,Password from tenant where Email=?");
            $stmt->bind_param("s",$email);
            $stmt->execute();
        
            // get username and password from database
            $stmt->bind_result($dbfirstname,$dbemail,$dbpassword);
            $stmt->fetch();
    
            // then check if they are matched or not
            if($email==$dbemail && $password==$dbpassword){
                // if valid input
                // then start a session
                session_start();          
                $_SESSION['Tenantname']=$dbfirstname;
                $_SESSION['Tenantemail'] = $dbemail;
                $_SESSION['welcome_message'] = "Welcome back $dbfirstname";
                // back to the tenant logined page
                header('Location:Tenant.php');
                exit();            
            }
            else{
                // else invalid user name or password
                // then stay the same page let user re-input
                header("Location: SignIn.html?error=password_incorrect");
                exit();  
            }
        }
        else if($role === "landlord"){
            // check if the user is existed in our database or not
            // by using php prepared statement logic to keep safe and avoid users' attack
            // check inputed name with email and password in our table
            $stmt=$con->prepare("SELECT FirstName,Email,Password from landlord where Email=?");
            $stmt->bind_param("s",$email);
            $stmt->execute();
        
            // get username and password from database
            $stmt->bind_result($dbfirstname,$dbemail,$dbpassword);
            $stmt->fetch();
    
            // then check if they are matched or not
            if($email==$dbemail && $password==$dbpassword){
                // if valid input
                // then start a session
                session_start();          
                $_SESSION['Landlordname']=$dbfirstname;
                $_SESSION['Landlordemail'] = $dbemail;
                $_SESSION['welcome_message'] = "Welcome back $dbfirstname";
                // back to the landlord logined page
                header('Location:Landlord.php');
                exit();            
            }
            else{
                // else invalid user name or password
                // then stay the same page let user re-input
                header("Location: SignIn.html?error=password_incorrect");
                exit();  
            }
        }
     }
     //fields are empty
     else{

         // then stay the same page let user re-input
         header("Location: SignIn.html?error=fields_empty");
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

$con->close();
?>