
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// login.php
session_start();

// Include the database configuration file
include 'config.php';

// Define variables to store user input
$email_uid = $password = '';
$email_uid_err = $password_err = '';

// Processing form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Email/UID
    if (empty(trim($_POST["email_uid"]))) {
        $email_uid_err = "Please enter your email or UID.";
    } else {
        $email_uid = trim($_POST["email_uid"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check if all input fields are error-free
    if (empty($email_uid_err) && empty($password_err)) {
        // Retrieve user data from the database based on email or UID
        $stmt = $pdo->prepare("SELECT id, email, uid, password FROM users WHERE email = ? OR uid = ?");
        $stmt->execute([$email_uid, $email_uid]);

        if ($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the password
            if (password_verify($password, $user["password"])) {
                // Password is correct, start a new session
                session_start();

                // Store data in session variables
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_email"] = $user["email"];
                $_SESSION["user_uid"] = $user["uid"];

                // Redirect to the user's dashboard
                header("Location: dashboard.php");
                exit;
            } else {
                // Password is incorrect
                $password_err = "Invalid password.";
            }
        } else {
            // No user found with the provided email or UID
            $email_uid_err = "No account found with this email or UID.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>AsumChat - Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-color:#1363c6">
			<div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
            <form class="login100-form validate-form" method="post">
					<span class="login100-form-title p-b-49">
						Login
					</span>

					<div class="wrap-input100 validate-input m-b-23" data-validate = "Username is reauired">
						<span class="label-input100">Email or UID:</span>
						<input class="input100" type="text" name="email_uid"  value="<?php echo htmlspecialchars($email_uid); ?>" placeholder="Type your username" required>
						<span class="focus-input100" data-symbol="&#xf206;"></span>
						<span class="text-danger"><?php echo $email_uid_err; ?></span>
                        <!-- Error message for email or UID goes here -->
					</div>

					<div class="wrap-input100 validate-input" data-validate="Password is required">
						<span class="label-input100">Password</span>
						<input class="input100" type="password" name="password" placeholder="Type your password" required>
						<span class="text-danger"><?php echo $password_err; ?></span>
                        <!-- Error message for password goes here -->
						<span class="focus-input100" data-symbol="&#xf190;"></span>
					</div>
					
					<div class="text-right p-t-8 p-b-31">
						<a href="#">
							Forgot password?
						</a>
					</div>
					
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn">
								Login
							</button>
						</div>
					</div>

					<div class="txt1 text-center p-t-54 p-b-20">
						<span>
							Or Sign Up Using
						</span>
					</div>

					<div class="flex-c-m">
						<a href="#" class="login100-social-item bg1" style="text-decoration:none">
							<i class="fa fa-facebook"></i>
						</a>

						<a href="#" class="login100-social-item bg2" style="text-decoration:none">
							<i class="fa fa-twitter"></i>
						</a>

						<a href="#" class="login100-social-item bg3" style="text-decoration:none">
							<i class="fa fa-google"></i>
						</a>
					</div>
					<div class="flex-col-c p-t-155" style="margin-top: -32%;">
						<span class="txt1 p-b-17">
							Or Sign Up Using
						</span>

						<a href="3sign_in_page.php" class="txt2">
							Sign Up
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>