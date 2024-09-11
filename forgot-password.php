<?php
    include "includes/db.php";

    if (isset($_POST['submit'])) {
        $email_address = $_POST['email_address'];

        $stmt = $db_connect->prepare("SELECT * FROM admin WHERE admin_email = ?");
        $stmt->bind_param("s", $email_address);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            $token = bin2hex(random_bytes(50)); // Generate a token
            $stmt = $db_connect->prepare("UPDATE admin SET reset_token = ? WHERE admin_email = ?");
            $stmt->bind_param("ss", $token, $email_address);
            $stmt->execute();

            // Send reset password email
            $to = $email_address;
            $subject = "Password Reset";
            $message = "Click this link to reset your password: http://yourdomain.com/reset_password.php?token=$token";
            $headers = "From: no-reply@yourdomain.com";

            mail($to, $subject, $message, $headers);

            echo "<div class='alert alert-success'>Password reset link has been sent to your email.</div>";
        } else {
            echo "<div class='alert alert-danger'>No account found with that email.</div>";
        }

        $stmt->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <h2 class="text-center">Forgot Password</h2>
            <hr/>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" class="form-control" name="email_address" required>
                </div>
                <div class="form-group mt-3">
                    <button class="btn btn-primary w-100" type="submit" name="submit">Send Reset Link</button>
                </div>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>

</body>
</html>
