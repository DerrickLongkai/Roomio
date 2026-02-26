<?php

    // display properties in the home page
    // read properties from database:dzt_db
    $conn = new mysqli("localhost", "root", "", "dzt_db");
    // if it is NOT connected successfully
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // read sql query: read properties from database
    $sql = "
    SELECT p.Id, p.EirCode, p.Landlord, p.Bedroom, p.Price, p.Availability,
           GROUP_CONCAT(pi.image_name) AS images
    FROM property p
    LEFT JOIN property_images pi ON p.Id = pi.property_id
    GROUP BY p.Id
    ";

    // run this query
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="All.css">
        <title>Roomio</title>
        <style>/* */
            /* set property style*/
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
            /* set property style*/
        </style>
    </head>
    <body><!---->
        <div id="page">
            <!--header-->
            <div id="header">
                <!--logo image-->
                <img src="images/logo.png" alt="Logo" style="width: 15%; height: auto;">        
            </div>
            <!--Main Navigation-->
            <div id="Nav">
                <ul>
                    <li><a href="Home.php">Home</a></li>
                    <li><a href="News.html">News</a></li>
                    <li><a href="Testimonial.php">Testimonials</a></li>
                    <li><a href="SignIn.html">Sign In</a></li>
                    <li><a href="images/FirstCV.pdf">About Us</a></li>
                    <li><a href="mailto:'longkai.zhang@student.griffith.ie'">Contact Us</a></li>
                </ul>
            </div>

            <!--display properties-->
            <?php
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $images = !empty($row['images']) ? explode(",", $row['images']) : [];
                    $mainImg = $images[0] ?? "placeholder.jpg"; // fallback if no images
        
                    echo "<div class='property'>";
        
                    // Main Image getting from folder:upload and by image_id from database
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
                    echo "</div>";
                }
            } 
            else {
                echo "<p>No properties found.</p>";
            }
            $conn->close();
            ?>

            <script>/*change images display position*/
                function changeImage(mainId, newSrc) {
                    document.getElementById(mainId).src = newSrc;
                }
            </script>        
        </div>
    </body>
</html>
