<?php
// friend_requests.php
session_start();

// Check if the user is authenticated, redirect to login if not
if (!isset($_SESSION["user_id"])) {
    header("Location: index.html");
    exit;
}

// Include the database configuration file
include 'config.php';

// Get user details from the database
$user_id = $_SESSION["user_id"];
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Retrieve pending friend requests for the user
$stmt_requests = $pdo->prepare("SELECT r.id, u.username as requester_username FROM friend_requests r JOIN users u ON r.requester_id = u.id WHERE r.receiver_id = ? AND r.status = 'pending'");
$stmt_requests->execute([$user_id]);
$requests = $stmt_requests->fetchAll(PDO::FETCH_ASSOC);

// Get user details from the database
$user_id = $_SESSION["user_id"];
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="dash.css">

	<title>AsumChat - Friend Requests</title>
    <style>
       

       .container2 {
    background: rgba(255, 255, 255, 0.1); /* Semi-transparent white */
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    width: 80%;
    max-width: 600px;
    margin: auto;
    margin-top: 50px;
    backdrop-filter: blur(10px); /* Apply blur effect */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle white border */
    height:300px;
	color: var(--dark);
}

.card-outline2 {
    background: rgba(255, 255, 255, 0.7); /* Semi-transparent white */
    border-radius: 10px;
    margin-bottom: 15px;
    padding: 15px;
    backdrop-filter: blur(10px); /* Apply blur effect */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle white border */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional: for depth */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height:auto;
	color:var(--dark);
	background-color: var(--light);
	align-items:center;
} 

.card-outline2 h2 {
    margin-bottom: 60px;
    margin-left:60px;
  
}
.content
{
    flex: 1;
}
.content p{
    margin-top:-10px;
}

.card-outline2 p {
    margin-bottom: 20px;
    margin-left:60px;
    margin-top:20px;
    color:red;
}
.left p
{
    color:red;
}


.btn-accept {
    background-color: #4CAF50; /* Green background */
    color: white; /* White text */
    padding: 10px 10px; /* Padding */
    border: none; /* No border */
    border-radius: 5px; /* Rounded corners */
    transition: background-color 0.3s, transform 0.3s; /* Smooth transition for hover effects */
    margin-top:90px;
    
}

.btn-accept:hover {
    background-color: #45a049; /* Darker green on hover */
    transform: scale(1.05); /* Slightly larger on hover */
}

.btn-reject {
    background-color: #f44336; /* Red background */
    color: white; /* White text */
    padding: 10px 10px; /* Padding */
    border: none; /* No border */
    border-radius: 5px; /* Rounded corners */
    transition: background-color 0.3s, transform 0.3s; /* Smooth transition for hover effects */
}

.btn-reject:hover {
    background-color: #da190b; /* Darker red on hover */
    transform: scale(1.05); /* Slightly larger on hover */
}

.ml-2 {
    margin-left: 8px; /* Adds spacing between buttons */
}
.button-group {
    /* Additional styling as needed */
    text-align: center; /* Center-align the buttons */
    padding: 10px 0; /* Add some padding at the top and bottom */
}
h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
            text-align: center;
            color: var(--dark);
            
        }

        form {
            margin-top: 25px;
            background: none; /* Override background for the form */
           
            
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            margin-bottom: 15px;
            
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            
           
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
            
        }

        h3 {
            margin-top: 20px;
            font-size: 18px;
            text-align: center;
            color: var(--dark);
           
        }

        a {
            color: #007bff;
            text-decoration: none;
           
        }

        a:hover {
            text-decoration: underline;
        }

.bx.bx-menu{
	font-size:  24px;
	margin-top:5px;
	font-weight: bold ;
}
.form-input{
			margin-bottom: 18px;
			margin-top: 22px;
	
}
.mohan{
            max-width: 450px;
            background-color: var(--light);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-left: 40%;
            font-family: var(--poppins);
            color: var(--dark);
            margin-top: 40px;
    
        }
		.form-input{
			margin-bottom: 18px;
			margin-top: 22px;
	
}
.bx.bx-menu{
	font-size:  24px;
	margin-top:5px;
	font-weight: bold ;
}


    </style>
</head>
<body>


	<!-- SIDEBAR -->
	<section id="sidebar">
	<a href="#" class="brand">
        <i class='bx '><img src="img/logo.png" style="width:60px;height:40px;"></i>
        <!-- Replace "Admin" with the fetched username -->
        <span class="text"><?php echo htmlspecialchars($user['username']); ?></span>
    </a>
		<ul class="side-menu top">
			<li >
				<a href="dashboard.php" style="text-decoration:none;">
					<i class='bx bxs-dashboard' ></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="chat.php" style="text-decoration:none;">
					<i class='bx bxs-message-dots' ></i>
					<span class="text">Message</span>
				</a>
			</li>
			<li>
				<a href="group_chat.php" style="text-decoration:none;">
					<i class='bx bxs-group' ></i>
					<span class="text">Group Chat</span>
				</a>
			</li>
			<li>
				<a href="friend_requests.php" style="text-decoration:none;">
					<i class='bx bx-user-check' ></i>
					<span class="text">Frined Requests</span>
				</a>
			</li>
			<li class="active">
				<a href="send_request.php" style="text-decoration:none;">
					<i class='bx bx-user-plus' ></i>
					<span class="text">Send Frined Requests</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<li>
				<a href="settings.php" style="text-decoration:none;">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="2login_page.php" class="logout" style="text-decoration:none;">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			
			<form action="#">
				<div class="form-input" style="margin-top:-3px;">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<a href="#" class="notification">
				<i class='bx bxs-bell' ></i>
				<span class="num">9</span>
			</a>
			<a href="#" class="profile">
				<img src="img/people.png">
			</a>
		</nav>
		<!-- NAVBAR -->
	
        <main >
            <div class="head-title">
			<div class="left">
					<h1>Send Friend Request</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#" style="text-decoration:none;">Friend Request</a>
						</li>
					</ul>
				</div>
			</div>

            <div class="container2 card-outline2"class="head-title" class="mohan">
                <div class="head-title">
			    	<div class="left">
                    <div class="container3" style="margin-top:-20px; text-align:center;" >
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="text-align:centner" >
            <label for="friend_uid"><h4>Friend's UID:</h4></label>
            <input type="text" id="friend_uid" name="friend_uid" required style="text-align:center;">
            <input type="submit" value="Send Request">
        </form>

        <h3>Back to <a href="dashboard.php">Dashboard</a></h3>
    </div>

				    </div>
			    </div>
            </div>
    </main>
	</section>
	<!-- CONTENT -->
	

	<script>
		const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

allSideMenu.forEach(item=> {
	const li = item.parentElement;

	item.addEventListener('click', function () {
		allSideMenu.forEach(i=> {
			i.parentElement.classList.remove('active');
		})
		li.classList.add('active');
	})
});




// TOGGLE SIDEBAR
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})







const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
	if(window.innerWidth < 576) {
		e.preventDefault();
		searchForm.classList.toggle('show');
		if(searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x');
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
		}
	}
})





if(window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}


window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
})



const switchMode = document.getElementById('switch-mode');

switchMode.addEventListener('change', function () {
	if(this.checked) {
		document.body.classList.add('dark');
	} else {
		document.body.classList.remove('dark');
	}
})
	</script>
</body>
</html>