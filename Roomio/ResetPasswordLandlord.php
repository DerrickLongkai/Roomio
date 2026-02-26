<?php
session_start(); 
// database connection
$con = mysqli_connect("localhost", "root", "", "dzt_db");

if (!$con) {
    echo "Connection failed ", mysqli_connect_error();
}

// Get the form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // First, we check data if they are setted and not empty
    // if is set and not empty
    if(isset($_POST["email"]) && !empty($_POST["email"]) && isset($_POST["password"]) && !empty($_POST["password"])
       && isset($_POST["newPassword"]) && !empty($_POST["newPassword"]) && isset($_POST["confirmNewPassword"]) && !empty($_POST["confirmNewPassword"])){

        // then check the new password and confirmed password must be same
        if($_POST["newPassword"] === $_POST["confirmNewPassword"]){

            // get input name and password and parse data through the method pass_input()
            $email = pass_input($_POST["email"]);
            $password = pass_input($_POST["password"]);
            $newPassword = pass_input($_POST["newPassword"]);
             // hashing password
            //$hashedpassword=password_hash($password,PASSWORD_DEFAULT);
   
            // then
            // check if the user is existed in our database or not
            // by using php prepared statement logic to keep safe and avoid users' attack
            // check inputed name with username in our table: Landlord
            $stmt1=$con->prepare("SELECT Email,Password FROM landlord WHERE Email=?");
            $stmt1->bind_param("s",$email);
            $stmt1->execute();
            
            // get username and password from database
            $stmt1->bind_result($dbemail,$dbpassword);
            $stmt1->fetch();
            
            // then check if they are matched or not
            //if($email == $dbemail && password_verify($password, $dbpassword)) // compare hashed passwords
            if($email==$dbemail && $password==$dbpassword)
            {
                    // it belongs to table: landlord
                    $stmt1->close();
                    // then update the password in table:landlord
                    $stmt2 = $con->prepare("UPDATE landlord SET Password = ? WHERE Email = ?");
                    if (!$stmt2) {
                        die("Prepare failed: " . $con->error);
                    }
            
                    $stmt2->bind_param("ss", $newPassword, $email);
                    if ($stmt2->execute()) {
                                
                         // print a message say: password has been updated successfully    
                         echo "<script>
                         alert('Your password has been updated successfully!');
                         window.location.href = 'Landlord.php';
                         </script>";
                        
                    } else {
                        echo "Error updating password: " . $stmt2->error;
                    }
                    $stmt2->close();
                
               
            }
         
            // not match any information in two tables
            else{
            
                 // print a message say: the name or current password you entered is invalid!      
                  // then stay the same page let user re-input
                 header("Location: ResetPasswordLandlord.html?error=incorrect_emailorpassword");
                 exit(); 
            }
        }
        else{
         // print a message say: the new password and confirmed password is difference
         // then stay the same page let user re-input
         // then stay the same page let user re-input
         header("Location: ResetPasswordLandlord.html?error=passwords_dismatch");
         exit(); 
        }
     

    }
    // if one of them is empty
    else{
         // print a message say: the name or password shouldn't be empty
         // then stay the same page let user re-input
          // then stay the same page let user re-input
         header("Location: ResetPasswordLandlord.html?error=fields_empty");
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