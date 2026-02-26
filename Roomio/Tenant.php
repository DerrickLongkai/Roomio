<?php
    // print the welcome message using session()
    session_start();
   
    if (isset($_SESSION['welcome_message'])) {
     echo "<script>alert('" . $_SESSION['welcome_message'] . "');</script>";
      unset($_SESSION['welcome_message']);
    }

    // --- 1. Database connection ---
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "dzt_db";
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // --- 2. read property SQL query---
    $sql = "
    SELECT p.Id, p.EirCode, p.Landlord, p.Bedroom, p.Price, p.Availability,
           GROUP_CONCAT(pi.image_name) AS images
    FROM property p
    LEFT JOIN property_images pi ON p.Id = pi.property_id
    WHERE 1
    ";

    // --- 3. Apply filters only if form is submitted ---
    
    if (!empty($_GET)) {
        // get price
        if (!empty($_GET['price'])) {
            $price_range = $_GET['price'];

            if ($price_range === 'under-500') {
                $sql .= " AND p.Price < 500";
            } 
            elseif ($price_range === '2000+') {
                $sql .= " AND p.Price >= 2000";
            } 
            elseif (preg_match('/^(\d+)-(\d+)$/', $price_range, $matches)) {
                $min = (float) $matches[1];
                $max = (float) $matches[2];
                $sql .= " AND p.Price BETWEEN $min AND $max";
            }
        }
        // get the number of bedrooms
        if (!empty($_GET['bedrooms'])) {
            $bedrooms = (int) $_GET['bedrooms'];
            $sql .= " AND p.Bedroom = $bedrooms";
        }
        // get the available length
        if (!empty($_GET['length'])) {
            $length_map = [
                '1'  => 'under 1 month',
                '3'  => '3 months',
                '6'  => '6 months',
                '12' => '12 months',
                '13' => '1 year+'
            ];

            $selected_length = $_GET['length'];

            if (isset($length_map[$selected_length])) {
                $availability_value = $conn->real_escape_string($length_map[$selected_length]);
                $sql .= " AND p.Availability LIKE '%$availability_value%'";
            }
        }
    }

    $sql .= " GROUP BY p.Id";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant</title>
    <link rel="stylesheet" href="All.css">
    <style>
        /*display the property */
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
    </style><!---->  
    </head>
    <body>
        <div id="page">
            <!--header-->
            <div id="header">
             <!--logo-->
            <img src="images/logo.png" alt="Logo" style="width: 15%; height: auto;">       
        </div>
        <!--Main navigation-->
        <div id="Nav">
            <ul>
                <li><a href="Home.php">Home</a></li>
                <li><a href="ResetPasswordTenant.html">Reset Password</a></li>
                <li><button type="button" id="toggleFormBtn" class="nav-btn" onclick="toggleAddPropertyForm()">Write Testimonial</button></li>
                <li><a href="LogoutTenant.php">Sign Out</a></li>
                <li><a href="images/FirstCV.pdf">About Us</a></li>
                <li><a href="mailto:'longkai.zhang@student.griffith.ie'">Contact Us</a></li>
            </ul>
        </div> 
        <div id="page">
        <!--header-->
        <div id="header">
            <div id="search">
            <form method="GET" id="searchForm">
                <h2>Search for Properties:</h2>
                <!-- Search Form -->
                 <!--get selected price-->
                <select name="price" id="rentalPrice">
                    <option value="">Price</option>
                    <option value="under-500" <?= (($_GET['price'] ?? '') == 'under-500') ? 'selected' : '' ?>>Under €500</option>
                    <option value="500-1000" <?= (($_GET['price'] ?? '') == '500-1000') ? 'selected' : '' ?>>€500–€1,000</option>
                    <option value="1000-1500" <?= (($_GET['price'] ?? '') == '1000-1500') ? 'selected' : '' ?>>€1,000–€1,500</option>
                    <option value="1500-2000" <?= (($_GET['price'] ?? '') == '1500-2000') ? 'selected' : '' ?>>€1,500–€2,000</option>
                    <option value="2000+" <?= (($_GET['price'] ?? '') == '2000+') ? 'selected' : '' ?>>€2,000+</option>
                </select>

                <!--get selected number of bedrooms-->
                <select name="bedrooms" id="bedrooms">
                    <option value="">Bedrooms</option>
                    <option value="1" <?= (($_GET['bedrooms'] ?? '') == '1') ? 'selected' : '' ?>>1</option>
                    <option value="2" <?= (($_GET['bedrooms'] ?? '') == '2') ? 'selected' : '' ?>>2</option>
                    <option value="3" <?= (($_GET['bedrooms'] ?? '') == '3') ? 'selected' : '' ?>>3</option>
                    <option value="4" <?= (($_GET['bedrooms'] ?? '') == '4') ? 'selected' : '' ?>>4+</option>
                </select>

                <!--get selected length-->
                <select name="length" id="tenancyLength">
                    <option value="">Length</option>
                    <option value="1" <?= (($_GET['length'] ?? '') == '1') ? 'selected' : '' ?>>Under 1 month</option>
                    <option value="3" <?= (($_GET['length'] ?? '') == '3') ? 'selected' : '' ?>>3 months</option>
                    <option value="6" <?= (($_GET['length'] ?? '') == '6') ? 'selected' : '' ?>>6 months</option>
                    <option value="12" <?= (($_GET['length'] ?? '') == '12') ? 'selected' : '' ?>>12 months</option>
                    <option value="13" <?= (($_GET['length'] ?? '') == '13') ? 'selected' : '' ?>>1 year+</option>
                </select>
                <!--seach button-->
                <button class="search-button" type="submit">Search</button>          
            </form>
            </div>
        </div>

        <!-- Add Testimonial Form -->
        <div id="UploadTestimonialForm">
            <form  name="f1" action="UploadTestimonial.php" method="POST">
                <div id="demo" style.display ='none'></div>
                <!--handle input errors-->
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
                    else if (params.get('error') === 'uploaded') {
                        result.innerHTML = '<p>Testimonial has been submitted successfully.<p>';  
                        result.style.backgroundColor = 'rgba(41, 217, 41, 1)';
                        result.style.padding = '1px';
                        result.style.borderRadius = '10px' 
                        result.style.display = 'block';    
                    }
                </script>
                <!--inputs for testimonial-->
                <h3>Write a testimonial:</h3>
                <table border="1">
                    <tr>
                        <td>Select a stayed date:</td>
                        <td><input type="date" name="date" id="date" ><br></td>
                        <!--script function to select purchase date only today or before today-->
                        <script>
                            // Get today's date in YYYY-MM-DD format
                            const commentdate = document.getElementById("date");
                            // setting date format:YYYY-MM-DD
                            const today = new Date().toISOString().split("T")[0]; 
                            // Set the max date to today
                            commentdate.max = today; 
                        </script>
                    </tr>
                    <tr>
                        <td>Comment:</td>
                        <td><textarea id="comment" name="comment" rows="10" cols="30">Say something......</textarea></td>
                    </tr>
                    <tr>
                        <td><button type="submit" id="submit">Submit</button></td>
                    </tr>
                </table>
            </form>
        </div>
    
        <script>//toggle the write testimonial button
            const form = document.getElementById("UploadTestimonialForm");
            const toggleBtn = document.getElementById("toggleFormBtn");

            // Show form function
            function showForm() {
                form.style.display = "block";
                toggleBtn.textContent = "Hide Write Testimonial";
                localStorage.setItem("formDisplayState", "block");
            }

            // Hide form function
            function hideForm() {
                form.style.display = "none";
                toggleBtn.textContent = "Write Testimonial";
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
        <!-- Property Results -->
        <?php
            // Check if the query returned any rows
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {// Loop through each row (property) in the result set
                    // Split images string into an array (if not empty), otherwise use an empty array
                    $images = !empty($row['images']) ? explode(",", $row['images']) : [];
                    // Select the first image as the main display image, or use a placeholder if none exist
                    $mainImg = $images[0] ?? "placeholder.jpg";

                    // Start the property container
                    echo "<div class='property'>";                    
                    // Display the main image with an ID that is unique to this property
                    echo "<img src='uploads/" . htmlspecialchars($mainImg) . "' class='main-image' id='main-{$row['Id']}'>";

                    // If there are multiple images, show them as thumbnails
                    if (!empty($images)) {
                        echo "<div class='thumbnails'>";
                        // Loop through all images and display them as clickable thumbnails
                        foreach ($images as $img) {
                            // When a thumbnail is clicked, it calls JavaScript function changeImage()
                            echo "<img src='uploads/" . htmlspecialchars($img) . "' onclick=\"changeImage('main-{$row['Id']}', 'uploads/" . htmlspecialchars($img) . "')\">";
                        }
                        echo "</div>";// close thumbnails container
                    }

                    // Display property details: EirCode, Bedrooms, Price, Availability
                    echo "<h3>EirCode: " . htmlspecialchars($row['EirCode']) . "</h3>";
                    echo "<p><strong>Bedrooms:</strong> " . htmlspecialchars($row['Bedroom']) . "</p>";
                    echo "<p><strong>Price:</strong> €" . htmlspecialchars(number_format($row['Price'], 2)) . "</p>";
                    echo "<p><strong>Availability:</strong> " . htmlspecialchars($row['Availability']) . "</p>";
                    echo "</div>";// close property container
                }
            }
            else {
                // Message shown if no properties were found in the database
                echo "<p>No properties found.</p>";
            }
        $conn->close();
        ?>

        <script>
            /**
            * changeImage() updates the "main" image for a property.
            * 
            * @param {string} mainId - The ID of the main <img> element (unique per property).
            * @param {string} newSrc - The new image source (thumbnail clicked).
            */
            function changeImage(mainId, newSrc) {
                // Find the main image element by its ID and update its source
                document.getElementById(mainId).src = newSrc;
            }
        </script>        
    </body>
</html>