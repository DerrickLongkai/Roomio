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
    // First, we check data if they are setted and not empty
    // if is set and not empty
    if(isset($_POST["eircode"]) && !empty($_POST["eircode"]) && isset($_POST["price"]) && !empty($_POST["price"])){
       
        // then check the EirCode format
        if(isValidEircode($_POST['eircode'])){

            $eirCode = pass_input($_POST['eircode']);
            $eirCode = strtoupper($eirCode);
            $bedrooms = pass_input($_POST['beds']);
            $length = pass_input($_POST['length']);
            $price = pass_input($_POST['price']);
            //Then check if property is existed
            //GET landlord email
            $email = $_SESSION["Landlordemail"];
            //echo " <p>Your email is $email</p>";

            
             
            // Then check if property is existed

                $stmt1 = $conn->prepare("SELECT EirCode from property where EirCode=?");
                $stmt1->bind_param("s",$eirCode);
                $stmt1->execute();
                $result1=$stmt1->get_result();
                // if username is found 
                if($result1->num_rows>0){
                    // print a message say: user already exists
                    // then stay the same page let user re-input
                    header("Location: Landlord.php?error=existed_property");
                    exit(); 
                }
                // else insert this user to our database
                else{
                    // Insert property details
                    $insertPropertySql = "INSERT INTO property(EirCode,Landlord, Bedroom, Price,Availability) VALUES (?, ?, ?, ?,?)";
                    $stmt2 = $conn->prepare($insertPropertySql);
                    if (!$stmt2) {
                        die("Prepare failed (property): " . $conn->error);
                    }
                    $stmt2->bind_param("ssids",$eirCode, $email,$bedrooms, $price, $length);
                    if (!$stmt2->execute()) {
                        die("Execute failed (property): " . $stmt2->error);
                    }

                    // Use the connection's insert_id (not the statement)
                    $property_id = $conn->insert_id;
                    $stmt2->close();

                    // --- handle uploaded photos ---
                    if (!empty($_FILES['photos']) && isset($_FILES['photos']['name']) && count($_FILES['photos']['name']) > 0) {
                    $target_dir = __DIR__ . '/uploads/';
                    if (!is_dir($target_dir)) {
                        if (!mkdir($target_dir, 0777, true)) {
                            die("Failed to create uploads directory: $target_dir");
                        }
                    }

                    // Check PHP settings if uploads fail (post_max_size, upload_max_filesize, max_file_uploads)

                    $allowed_ext = ['jpg','jpeg','png','gif'];
                    $totalFiles = count($_FILES['photos']['name']);
                    // Optionally limit to 5 files
                    $maxFiles = 5;
                    $filesToProcess = min($totalFiles, $maxFiles);

                    for ($i = 0; $i < $filesToProcess; $i++) {
                        $tmpName = $_FILES['photos']['tmp_name'][$i];
                        $origName = basename($_FILES['photos']['name'][$i]);

                        // skip if no file selected
                        if (empty($origName) || !is_uploaded_file($tmpName)) {
                            continue;
                        }

                        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                        if (!in_array($ext, $allowed_ext)) {
                            // skip unsupported file types
                            continue;
                        }

                        // create a unique filename to avoid collisions
                        $uniqueName = uniqid('prop_') . '.' . $ext;
                        $destPath = $target_dir . $uniqueName;

                        if (move_uploaded_file($tmpName, $destPath)) {
                            // Insert filename into property_images table
                            $insertImgSql = "INSERT INTO property_images (property_id, image_name) VALUES (?, ?)";
                            $stmtImg = $conn->prepare($insertImgSql);
                            if (!$stmtImg) {
                                // Log and continue; don't break everything for one failed statement
                                error_log("Prepare failed (image insert): " . $conn->error);
                                continue;
                            }
                
                            $stmtImg->bind_param("is", $property_id, $uniqueName);
                
                            if (!$stmtImg->execute()) {
                    
                                error_log("Execute failed (image insert): " . $stmtImg->error);
                
                            }
                
                            $stmtImg->close();
            
                        } 
                        else {
                
                            error_log("Failed to move uploaded file to: $destPath");
            
                        }
        
                    } // end foreach files
    
                } // end if files

             
                //uploaded successfully
                header("Location: Landlord.php?error=uploaded");
                exit();
                
            }
                
            $stmt1->close();
            
        }
        else{
            //invalid eirCode
             header("Location: Landlord.php?error=invalid_eirCode");
             exit();
        }

    } 
    else{
        // empty fields
         // then stay the same page let user re-input
         header("Location: Landlord.php?error=fields_empty");
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
function isValidEircode($eircode) {
    // Remove spaces before checking
    $eircode = strtoupper(trim($eircode));

    // Pattern: 1 letter, 2 digits, optional space, then 4 alphanumeric characters
    $pattern = '/^[A-Z][0-9]{2}\s?[0-9A-Z]{4}$/';

    return preg_match($pattern, $eircode) === 1;
}

?>