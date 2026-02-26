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
    if(isset($_POST["firstname"]) && !empty($_POST["firstname"]) && isset($_POST["lastname"]) && !empty($_POST["lastname"])
    && isset($_POST["email"]) && !empty($_POST["email"]) && isset($_POST["password"]) && !empty($_POST["password"])
    && isset($_POST["confirmPassword"]) && !empty($_POST["confirmPassword"]) && isset($_POST["role"]) && !empty($_POST["role"])
     ){

        // then check if password and confirmPassword are equal or not
        // then check the new password and confirmed password must be same
        if($_POST["password"] == $_POST["confirmPassword"]){

            // get input name and password and parse data through the method pass_input()
            $firstname = pass_input($_POST["firstname"]);
            $lastname = pass_input($_POST["lastname"]);
            $email = pass_input($_POST["email"]);
            $password = pass_input($_POST["password"]);
            $role = pass_input($_POST['role']);
             // Sanitize input
             $role = htmlspecialchars($role);
            // hashing password
            //$hashedpassword=password_hash($password,PASSWORD_DEFAULT);

            // then check which role is selected
            if($role === "tenant"){

                // The email is the primary key
                $stmt1 = $con->prepare("SELECT Email from tenant where Email=?");
                $stmt1->bind_param("s",$email);
                $stmt1->execute();
                $result1=$stmt1->get_result();
                // if username is found 
                if($result1->num_rows>0){
                    // print a message say: user already exists
                    // then stay the same page let user re-input
                    header("Location: SignUp.html?error=existed_user");
                    exit(); 
                }
                // else insert this user to our database
                else{
                    $stmt2=$con->prepare("INSERT INTO tenant(FirstName,LastName,Email,Password) VALUES(?,?,?,?)");
                    $stmt2->bind_param("ssss",$firstname,$lastname,$email,$password);
                    $result2=$stmt2->execute();
                    if($result2){   
                        // then start a session
                        session_start();
                        $_SESSION['tenantname']=$firstname;

                        // then using java script to print an alert welcome message and then jump back to home page
                        echo "<script>
                        alert('Welcome to Roomio " . $_SESSION['tenantname'] . " You have signed up successfully. Now you can sign in');
                         window.location.href = 'Home.php';
                        </script>";
               
                    }
                    else {
                        echo "Error sign up: " . $stmt->error;
                    }
                    $stmt2->close();
                }
                $stmt1->close();

            }
            // selected landlord
            else if($role === "landlord"){

                $stmt3 = $con->prepare("SELECT Email from landlord where Email=?");
                $stmt3->bind_param("s",$email);
                $stmt3->execute();
                $result3=$stmt3->get_result();
                // if username is found 
                if($result3->num_rows>0){
                    // print a message say: user already exists
                    // then stay the same page let user re-input
                    header("Location: SignUp.html?error=existed_user");
                    exit(); 
                }
                // else insert this user to our database
                else{
                    $stmt4=$con->prepare("INSERT INTO landlord(FirstName,LastName,Email,Password) VALUES(?,?,?,?)");
                    $stmt4->bind_param("ssss",$firstname,$lastname,$email,$password);
                    $result4=$stmt4->execute();
                    if($result4){   
                        // then start a session
                        session_start();
                        $_SESSION['landlordname']=$firstname;

                        // then using java script to print an alert welcome message and then jump back to home page
                        echo "<script>
                        alert('Welcome to Roomio " . $_SESSION['landlordname'] . " You have signed up successfully. Now you can sign in.');
                        window.location.href = 'Home.php';
                        </script>";
                    }
                    else {
                        echo "Error sign up: " . $stmt->error;
                    }
                    $stmt4->close();
                }    
                $stmt3->close();
            }
        }
        else{
         // print a message say: the new password and confirmed password is difference
         // then stay the same page let user re-input
        // then stay the same page let user re-input
         header("Location: SignUp.html?error=password_mismatch");
         exit(); 
        }

    }  
    // else some fields are empty
    else{      
        // then stay the same page let user re-input
         header("Location: SignUp.html?error=fields_empty");
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
