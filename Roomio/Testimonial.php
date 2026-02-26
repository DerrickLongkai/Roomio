<?php

    // display testimonial
    // 1. Database connection
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "dzt_db";
    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Query testimonials with tenant firstname
    $sql = "SELECT tenant.FirstName, testimonial.Date, testimonial.Comment
        FROM testimonial
        JOIN tenant ON testimonial.Tenant = tenant.Email
        ORDER BY testimonial.date DESC";

    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Testimonials</title>
        <link rel="stylesheet" href="All.css">
    <!---->
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f5f7fa;
                margin: 0;
                padding: 20px;
            }
            .testimonial-container {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
            }
            .testimonial-card {
                background: white;
                border-radius: 10px;
                padding: 20px;
                width: 300px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                transition: transform 0.2s ease-in-out;
            }
            .testimonial-card:hover {
                transform: translateY(-5px);
            }
            .testimonial-header {
                display: flex;
                justify-content: space-between;
                font-size: 14px;
                color: #666;
                margin-bottom: 10px;
            }
            .testimonial-name {
                font-weight: bold;
                color: #333;
            }
            .testimonial-comment {
                font-size: 16px;
                color: #555;
                line-height: 1.4;
            }
        </style>
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
                    <li><a href="News.html">News</a></li>
                    <li><a href="SignIn.html">Sign in</a></li>
                    <li><a href="images/FirstCV.pdf">About Us</a></li>
                    <li><a href="mailto:'longkai.zhang@student.griffith.ie'">Contact Us</a></li>
                </ul>
            </div>

            <!--display testimonial-->
            <h1>What Our Tenants Say</h1>
            <div class="testimonial-container">
            <?php
               
                // 3. Display testimonials in card format
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='testimonial-card'>";
                        echo "  <div class='testimonial-header'>";
                        echo "      <span class='testimonial-name'>" . htmlspecialchars($row['FirstName']) . "</span>";
                        echo "      <span>" . htmlspecialchars(date("d M, Y", strtotime($row['Date']))) . "</span>";
                        echo "  </div>";
                        echo "  <div class='testimonial-comment'>" . htmlspecialchars($row['Comment']) . "</div>";
                        echo "</div>";
                    }
                } 
                else {
                    echo "<p>No testimonials found.</p>";
                }
    
                $conn->close();

            ?>
            </div>

        </div>
    </body>
</html>