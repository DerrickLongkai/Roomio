<?php
    session_start();// start a session from landlord

    // database connection
    $conn = new mysqli("localhost", "root", "", "dzt_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // get this property id
    $property_id = $_POST['property_id'] ?? null;

    if (!$property_id) {
        echo "No property selected.";
    exit;
    }

    // Delete images from server
    $imageQuery = "SELECT image_name FROM property_images WHERE property_id = ?";
    $stmt = $conn->prepare($imageQuery);
    $stmt->bind_param("i", $property_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        
        $filePath = __DIR__ . "/uploads/" . $row['image_name'];
        if (file_exists($filePath)) {
            unlink($filePath); // delete image file
        }
    }

    // Delete image records
    $deleteImages = "DELETE FROM property_images WHERE property_id = ?";
    $stmt = $conn->prepare($deleteImages);
    $stmt->bind_param("i", $property_id);
    $stmt->execute();

    // Delete property record
    $deleteProperty = "DELETE FROM property WHERE Id = ?";
    $stmt = $conn->prepare($deleteProperty);
    $stmt->bind_param("i", $property_id);

    if ($stmt->execute()) {
        echo "<script>alert('Property is deleted successfully.'); window.location.href='Landlord.php';</script>";
    } 
    else {
        echo "Error deleting property: " . $conn->error;
    }

    $conn->close();
?>
