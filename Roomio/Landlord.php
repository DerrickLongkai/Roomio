<?php
    // print the welcome message using session()
    session_start();
    
   // Show welcome alert if message exists
   if (!empty($_SESSION['welcome_message'])) {
        echo "<script>alert('" . addslashes($_SESSION['welcome_message']) . "');</script>";
        unset($_SESSION['welcome_message']); // remove so it only shows once
    }

    // Get landlord email from session
    $email = $_SESSION['Landlordemail'];
    
    // display properties
    $conn = new mysqli("localhost", "root", "", "dzt_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "
    SELECT p.Id, p.EirCode, p.Landlord, p.Bedroom, p.Price, p.Availability,
           GROUP_CONCAT(pi.image_name) AS images
    FROM property p
    LEFT JOIN property_images pi ON p.Id = pi.property_id
    WHERE p.Landlord = '$email'
    GROUP BY p.Id
    ";
    
    $result = $conn->query($sql);


?>
<!DOCTYPE html>
<html lang="en">
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LandLord</title>
        <link rel="stylesheet" href="All.css"> 
        <style>
            .property h3 {
                font-size: 16px; /* Smaller than default heading size */
                font-family: Arial, sans-serif;
            }

            .property p {
                font-size: 14px; /* Paragraphs stay normal */
               font-family: Arial, sans-serif;
            }
            .property {
                border: 1px solid #ccc;
                margin: 20px;
                padding: 15px;
                width: 250px;
                display: inline-block;
                vertical-align: top;
            }
            .main-image {
                width: 100%;
                height: 150px;
                object-fit: cover;
                border: 1px solid #ddd;
                margin-bottom: 10px;
            }
            .thumbnails {
                display: flex;
                gap: 5px;
            }
            .thumbnails img {
                width: 45px;
                height: 50px;
                object-fit: cover;
                cursor: pointer;
                border: 2px solid transparent;
            }
            .thumbnails img:hover {
                border-color: #007BFF;
            }
            h3 {
                margin: 0;
            }
            .property #managebtn{
                background: none;
                border: none;
                border-radius: 8px;
                background-color: #007BFF; /* Blue button */
                color: #fff; /* White text */
                cursor: pointer;
                transition: background-color 0.3s ease, transform 0.3s ease;
                padding:4px;
            }
            .property #managebtn:hover{
                background-color: #0056b3; /* Darker blue on hover */
            }
        </style>
    
    <!---->
    </head>
    <body>
        <div id="page">
            <!--header-->
            <div id="header">
                <!--logo-->
                <img src="images/logo.png" alt="Logo" style="width: 15%; height: auto;"> 
            </div>
            <div id="Nav">
                <ul>
                    <li><a href="Home.php">Home</a></li>
                    <li><a href="ResetPasswordLandlord.html">Reset Password</a></li>
                    <li><button type="button" id="toggleFormBtn" class="nav-btn" onclick="toggleAddPropertyForm()">Upload Property</button></li>
                    <li><a href="LogoutLandlord.php">Sign Out</a></li>
                    <li><a href="images/FirstCV.pdf">About Us</a></li>
                    <li><a href="mailto:'longkai.zhang@student.griffith.ie'">Contact Us</a></li>
                </ul>
            </div>

            <!-- Add Property Form -->
            <div id="UploadPropertyForm">
                <form  name="f1" action="UploadProperty.php" method="POST" enctype="multipart/form-data">
                    <div id="demo" style.display ='none'></div>
                    <script>
                        const params = new URLSearchParams(window.location.search);
                        var result = document.getElementById('demo');
                    
                        if (params.get('error') === 'fields_empty') {                        
                            result.innerHTML = '<p>Sorry, fields can not be blank.<p>';
                            result.style.backgroundColor = 'rgba(237, 62, 50, 1)';
                            result.style.padding = '1px';
                            result.style.borderRadius = '10px'
                            result.style.display = 'block';
                          
                        }
                        else if (params.get('error') === 'invalid_eirCode') {
                            result.innerHTML = '<p>Sorry, The EirCode format is incorrect.<p>';  
                            result.style.backgroundColor = 'rgba(237, 62, 50, 1)';
                            result.style.padding = '1px';
                            result.style.borderRadius = '10px'
                            result.style.display = 'block';    
                        }
                        else if (params.get('error') === 'existed_property') {
                            result.innerHTML = '<p>Sorry, The EirCode you entered is already existed.<p>';
                            result.style.backgroundColor = 'rgba(237, 62, 50, 1)';
                            result.style.padding = '1px';
                            result.style.borderRadius = '10px'   
                            result.style.display = 'block';    
                        }
                        else if (params.get('error') === 'uploaded') {
                            result.innerHTML = '<p>Property has been uploaded successfully.<p>';  
                            result.style.backgroundColor = 'rgba(41, 217, 41, 1)';
                            result.style.padding = '1px';
                            result.style.borderRadius = '10px' 
                            result.style.display = 'block';    
                        }
                    </script>
      
                    <h3>Upload Property:</h3>
                    <table border="1">
                        <tr>
                            <td>EirCode</td>
                            <td><input type="text" name="eircode"></td>
                        </tr>
                        <tr>
                            <td>Number of Bedrooms:</td>
                            <td> <select id="beds" name="beds" required>
                                <option value="1">1 Bedroom</option>
                                <option value="2">2 Bedrooms</option>
                                <option value="3">3 Bedrooms</option>
                                <option value="4">4+ Bedrooms</option>
                            </select></td>
                        </tr>
                        <tr>
                            <td>Available Length:</td>
                            <td> <select id="length" name="length" required>
                                <option value="under 1 month">under 1 month</option>
                                <option value="1-3 months">1-3 months</option>
                                <option value="3-6 months">3-6 months</option>
                                <option value="6-12 months">6-12 months</option>
                                <option value="1 year+">1 year+</option>
                            </select></td>
                        </tr>
                        <tr>
                            <td>Rental Price(€/M):</td>
                            <td><input type="number" name="price"></td>
                        </tr>
                        <tr>
                            <td>Upload photos(up to 5):</td>
                            <td><input type="file" id="file" name="photos[]" accept="image/*" multiple></td>
                        </tr>
                        <tr>
                            <td><button type="submit" id="submit">Upload This Property</button></td>
                        </tr>
                    </table>
                </form>
            </div>

            <script>
           
                const form = document.getElementById("UploadPropertyForm");
                const toggleBtn = document.getElementById("toggleFormBtn");

                // Show form function
                function showForm() {
                    form.style.display = "block";
                    toggleBtn.textContent = "Hide Property Form";
                    localStorage.setItem("formDisplayState", "block");
                }

                // Hide form function
                function hideForm() {
                    form.style.display = "none";
                    toggleBtn.textContent = "Upload Property";
                    localStorage.setItem("formDisplayState", "none");
                }

                // Toggle form visibility when toggleBtn clicked
                toggleBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    if (form.style.display === "none") {
                        showForm();
                    } 
                    else {
                        hideForm();
                    }
                });

                // Hide form on clicking any other button except toggleBtn and submit button inside form
                document.querySelectorAll("button").forEach(button => {
                    if (button !== toggleBtn && button.type !== "submit") {
                        button.addEventListener("click", () => {
                            hideForm();
                        });
                    }
                });

                // Hide form on clicking any link (<a>)
                document.querySelectorAll("a").forEach(link => {
                    link.addEventListener("click", () => {
                        hideForm();
                    });
                });

                // On form submit, keep form visible after reload
                form.addEventListener("submit", () => {
                    localStorage.setItem("formDisplayState", "block");
                });

                // On page load, restore form visibility state
                window.addEventListener("DOMContentLoaded", () => {
                    if (localStorage.getItem("formDisplayState") === "block") {
                        showForm();
                    } 
                    else {
                        hideForm();
                    }
                });
            </script>

            <!--display properties-->
            <h1>My Property<h1>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $images = !empty($row['images']) ? explode(",", $row['images']) : [];
                    $mainImg = $images[0] ?? "placeholder.jpg"; // fallback if no images
        
                    echo "<div class='property'>";
        
                    // Main Image
                    echo "<img src='uploads/" . htmlspecialchars($mainImg) . "' class='main-image' id='main-{$row['Id']}'>";
        
                    // Thumbnails
                    if (!empty($images)) {
                        echo "<div class='thumbnails'>";
                        foreach ($images as $img) {
                            echo "<img src='uploads/" . htmlspecialchars($img) . "' onclick=\"changeImage('main-{$row['Id']}', 'uploads/" . htmlspecialchars($img) . "')\">";
                        }
                        echo "</div>";
                    }
        
                    // Property details
                    echo "<h3>EirCode: " . htmlspecialchars($row['EirCode']) . "</h3>";
                    echo "<p><strong>Bedrooms:</strong> " . htmlspecialchars($row['Bedroom']) . "</p>";
                    echo "<p><strong>Price:</strong> €" . htmlspecialchars($row['Price']) . "</p>";
                    echo "<p><strong>Availability:</strong> " . htmlspecialchars($row['Availability']) . "</p>";   
                    // delete button 
                    echo "<form action='DeleteProperty.php' method='POST' onsubmit=\"return confirm('Are you sure you want to delete this property?');\">";
                    echo "<input type='hidden' name='property_id' value='" . htmlspecialchars($row['Id']) . "'>";
                    echo "<button type='submit' id='managebtn'>Delete Property</button>";
                    echo "</form>";
                    echo "</div>";
                }
            } 
            else {
                echo "<p>No properties found.</p>";
            }
            $conn->close();
        ?>

        <script>
            function changeImage(mainId, newSrc) {
                document.getElementById(mainId).src = newSrc;
            }
        </script>      
</div>
</body>
</html>