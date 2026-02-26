<?php
// log out page 
session_start();
$username = $_SESSION['Landlordname'];
// destroy the sesseion
session_destroy();

// print an alert message then back to home page
echo "<script>
    alert('User: $username has logged out.');
    window.location.href = 'Home.php';
</script>";
exit();
?>