<?php
    include "includes/db.php";
    session_start();

    if (isset($_POST['login'])) {
        $email_address = $_POST['email_address'];
        $password = sha1($_POST['password']);
        $remember_me = isset($_POST['remember_me']);

        $stmt = $db_connect->prepare("SELECT * FROM admin WHERE admin_email = ? AND admin_pass = ?");
        $stmt->bind_param("ss", $email_address, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['admin_name'];

            // Set cookies if "Remember Me" is checked
            if ($remember_me) {
                setcookie('admin_email', $email_address, time() + (86400 * 30), "/");  // 30 days
                setcookie('admin_password', $_POST['password'], time() + (86400 * 30), "/");  // 30 days
            }

            header("Location: dashboard.php");
        } else {
            $error_msg = "<div class='alert alert-danger'>Invalid email or password!</div>";
        }

        $stmt->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <h2 class="text-center">Login</h2>
            <hr/>
            <?php if (isset($error_msg)) { echo $error_msg; } ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" class="form-control" name="email_address" value="<?php echo isset($_COOKIE['admin_email']) ? $_COOKIE['admin_email'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" value="<?php echo isset($_COOKIE['admin_password']) ? $_COOKIE['admin_password'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <input type="checkbox" name="remember_me" <?php if (isset($_COOKIE['admin_email'])) { echo "checked"; } ?>> Remember Me
                </div>

                <div class="form-group mt-3">
                    <button class="btn btn-primary w-100" type="submit" name="login">Login</button>
                </div>

                <div class="form-group mt-3 text-center">
                    <a href="forgot-password.php">Forgot Password?</a>
                </div>
            </form>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>

</body>
</html>
