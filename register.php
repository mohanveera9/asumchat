<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AsumChat - Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your Custom CSS -->
    <!-- Include any additional custom styles you may have here -->
    <style>
        body {
            /*background: linear-gradient(to right, #728FCE, #C34A2C, #342D7E);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            color: black;*/
            background-color:#1363c6;
            width: 100%;  
            min-height: 100vh;
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            padding: 15px;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        .glass-morphism-form {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 20px; /* Reduced padding */
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 80%; /* Reduced width */
            max-width: 400px; /* Set a maximum width */
            margin: auto; /* Center the form horizontally */
            margin-top: 50px; /* Adjusted margin from the top */
        }

        .glass-morphism-form h2 {
            color: #fff; /* Text color changed to white */
            margin-bottom: 20px;
        }

        .white-box {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .white-box h3 {
            color: #333; /* Text color changed to dark */
        }

        .glass-morphism-form .btn-primary {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        .glass-morphism-form .btn-primary:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-5 glass-morphism-form">
                 
        
                <div class="white-box">
                    <h3>Your UID is:</h3>
                    <p>    <?php
                // register.php
                
                // Define the function to generate a 10-digit UID
                function generateUID() {
                    // Logic to generate a 10-digit unique ID
                    return mt_rand(1000000000, 9999999999);
                }
                
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Retrieve and validate form data
                    $email = $_POST["email"] ?? null;
                    $phone = $_POST["phone"] ?? null;
                    $username = $_POST["username"] ?? null;
                    $uid = generateUID();
                
                    // Validation checks - you may need to customize these based on your requirements
                    if (empty($email) || empty($phone) || empty($username)) {
                        die("Please fill out all required fields.");
                    }
                
                    // Database connection
                    $dbName = "venkatesh2"; // Update with your actual database name
                
                    try {
                        $pdo = new PDO("mysql:host=localhost;dbname=$dbName", "root", "");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                        // Save user data to the database
                        $stmt = $pdo->prepare("INSERT INTO users (email, phone, username, uid) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$email, $phone, $username, $uid]);
                
                        // Display the UID and a button to copy and set password
                        echo " $uid<br>";
                        echo "<form id='copyForm' action='set_password.php' method='post'>";
                        echo "<input type='hidden' name='copiedUID' value='$uid'>";
                        echo "<button type='button' onclick='copyUID()'>Copy UID</button>";
                        echo "</form>";
                
                    } catch (PDOException $e) {
                        die("Connection failed: " . $e->getMessage());
                    }
                }
                ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    // JavaScript function to copy the UID to the clipboard
    function copyUID() {
        var copyText = document.createElement("input");
        copyText.value = document.querySelector("input[name='copiedUID']").value;
        document.body.appendChild(copyText);
        copyText.select();
        document.execCommand("copy");
        document.body.removeChild(copyText);
        // Redirect to setpassword.php after copying
        document.getElementById('copyForm').submit();
    }
    </script>
</body>
</html>