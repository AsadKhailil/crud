<?php
    include "includes/db.php";

    if (isset($_GET['token'])) {
        $token = $_GET['token'];

        if (isset($_POST['submit'])) {
            $new_password = sha1($_POST['new_password']);
            $confirm_password = sha1($_POST['confirm_password']);

            if ($new_password === $confirm_password) {
                $stmt = $db_connect->prepare("UPDATE admin SET admin_pass = ?, reset_token = NULL WHERE reset_token = ?");
                $stmt->bind_param("ss", $new_password, $token);
                $stmt->execute();

                echo "<div class='alert alert-success'>Your password has been reset successfully.</div>";
            } else {
                echo "<div class='alert alert-danger'>Passwords do not match.</div>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <h2 class="text-center">Reset Password</h2>
            <hr/>
            <form action="" method="POST">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" class="form-control" name="new_password" required>
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                </div>
                <div class="form-group mt-3">
                    <button class="btn btn-primary w-100" type="submit" name="submit">Reset Password</button>
                </div>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>

</body>
</html>
