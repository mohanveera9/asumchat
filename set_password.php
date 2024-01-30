<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Chat App - Set Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add your style.css or use inline styles here for custom styles -->

    <style>
        body {
            background: linear-gradient(to right, #728FCE, #C34A2C, #342D7E);
            color: #fff;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .card {
            width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
           
            color: #000; /* Set Password text color */
        }

        .btn-primary {
            background-color: #4CAF50;
            border-color: #007BFF;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .form-control {
            border-color: #ced4da;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow">
        <div class="card-header bg-#FF6B6B text-center">
            <h3 >Set Password</h3>
        </div>
        <div class="card-body">
           
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="needs-validation" novalidate>
                <div class="form-group">
                    <label for="uid" class="font-weight-bold" style="color:black;">UID:</label>
                    <input type="text" class="form-control" name="uid" value="<?php echo isset($_POST['uid']) ? htmlspecialchars($_POST['uid']) : ''; ?>" placeholder="Paste copied UID here" required>
                    <!-- Removed warning message display -->
                </div>

                <div class="form-group">
                    <label for="password" class="font-weight-bold" style="color:black;">Password:</label>
                    <input type="password" class="form-control" name="password" required>
                    <!-- Removed warning message display -->
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="font-weight-bold" style="color:black;">Confirm Password:</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="confirm_password" id="passwordInput" required>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fa fa-eye" id="togglePassword"></i>
                            </span>
                        </div>
                    </div>
                    <?php
error_reporting(E_ERROR | E_PARSE);
?>
                </div>

                <div class="text-center">
                    <button type="submit" style="background-color:#4CAF50;">Set Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
            // Include the database configuration file
            include 'config.php';

            // Define variables to store user input
            $uid = $password = $confirm_password = '';
            $uid_err = $password_err = $confirm_password_err = '';

            // Processing form data when the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Validate UID
                if (empty(trim($_POST["uid"]))) {
                    $uid_err = "Please enter your UID.";
                } else {
                    $uid = trim($_POST["uid"]);
                }

                // Validate password
                if (empty(trim($_POST["password"]))) {
                    $password_err = "Please enter a password.";
                } elseif (strlen(trim($_POST["password"])) < 6) {
                    $password_err = "Password must have at least 6 characters.";
                } else {
                    $password = trim($_POST["password"]);
                }

                // Validate confirm password
                if (empty(trim($_POST["confirm_password"]))) {
                    $confirm_password_err = "Please confirm the password.";
                } else {
                    $confirm_password = trim($_POST["confirm_password"]);
                    if (empty($password_err) && ($password != $confirm_password)) {
                        $confirm_password_err = "Password did not match.";
                    }
                }

                // Check if all input fields are error-free
                if (empty($uid_err) && empty($password_err) && empty($confirm_password_err)) {
                    // Update the user's password in the database
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE uid = ?");
                    if ($stmt->execute([$hashed_password, $uid])) {
                        // Password set successfully, redirect to login page
                        header("Location: 2(1)login_page.php");
                        exit();
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                }
            }
            ?>


<!-- Include the Bootstrap JS scripts at the end of your HTML file -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('passwordInput');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
</script>

</body>
</html>