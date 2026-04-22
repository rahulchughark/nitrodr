<?php
// Define your data
$contactNumber = "9876653412"; // Contact number
$city = "Barpeta";
$schoolName = "Example School";
$annualFees = "1234234";

// Define the message you want to send
$message = "Hello, I am inquiring about $schoolName located in $city. The annual fees are $annualFees. Please let me know more details.";

// Encode the message for the URL
$encodedMessage = urlencode($message);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Page</title>
    <!-- Link to Font Awesome for WhatsApp Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div>
    <p>Contact No.: <?php echo $contactNumber; ?>
        <a href="https://wa.me/<?php echo $contactNumber; ?>?text=<?php echo $encodedMessage; ?>" target="_blank">
            <i class="fab fa-whatsapp" style="color: #25D366; font-size: 20px; margin-left: 10px;"></i>
        </a>
    </p>
</div>

</body>
</html>
