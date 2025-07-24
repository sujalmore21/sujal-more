<?php
$alreadyRegistered = false;
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "seo-webinar");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize input
    $name   = trim($_POST['name']);
    $email  = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);

    // Check if user already registered (by email or mobile)
    $check = $conn->prepare("SELECT id FROM registration WHERE email = ? OR mobile = ?");
    $check->bind_param("ss", $email, $mobile);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $alreadyRegistered = true;
    } else {
        // Save to database
        $stmt = $conn->prepare("INSERT INTO registration (name, email, mobile) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $mobile);
        $stmt->execute();
        $stmt->close();

        $success = true;

        // Redirect to EzBuss Payment Gateway
        $redirectUrl = "https://easebuzz.in/quickpay/vphrrjnxob/";
        header("Location: $redirectUrl");
        exit();
    }

    $check->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register for Webinar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: auto;
            padding: 2rem;
        }

        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            background: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background: #45a049;
        }

        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 6px;
        }

        .error {
            background: #f8d7da;
            color: #842029;
        }

        .success {
            background: #d1e7dd;
            color: #0f5132;
        }
    </style>

    <script>
        function validateForm() {
            const mobile = document.forms["webinarForm"]["mobile"].value;
            if (!/^\d{10}$/.test(mobile)) {
                alert("Mobile number must be exactly 10 digits.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

    <h2>SEO Webinar Registration</h2>

    <?php if ($alreadyRegistered): ?>
        <div class="message error">You’ve already registered with this email or mobile number.</div>
    <?php elseif ($success): ?>
        <div class="message success">Registration successful! Redirecting to payment...</div>
    <?php endif; ?>

    <form name="webinarForm" method="POST" action="" onsubmit="return validateForm()">
        <label for="name">Full Name:</label>
        <input type="text" name="name" required pattern="[a-zA-Z\s]{2,}" title="Enter a valid name (letters only)">

        <label for="email">Email Address:</label>
        <input type="email" name="email" required>

        <label for="mobile">Mobile Number:</label>
        <input type="text" name="mobile" required pattern="[0-9]{10}" title="10-digit mobile number only">

        <button type="submit">Register & Pay ₹99</button>
    </form>

</body>
</html>
