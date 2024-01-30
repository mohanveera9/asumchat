<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="dash.css">
	<style>
		/* Notifications Section */
.notifications {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 20px;
}

.notifications h2 {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 20px;
}

.notifications form {
    max-width: 400px;
}

.notifications .mb-3 {
    margin-bottom: 20px;
}

.notifications label {
    font-size: 16px;
    font-weight: 500;
}

.notifications .form-control {
    width: 100%;
    padding: 10px;
    margin-top: 8px;
    margin-bottom: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

.notifications .form-check-label {
    font-size: 16px;
    font-weight: 500;
    margin-left: 8px;
}

.notifications .btn-primary {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.notifications .btn-primary:hover {
    background-color: #0056b3;
}

/* Additional styles for the settings sections */
.settings-section {
    margin-bottom: 40px;
}
.notifications{
	color:var(--dark);
	background-color: var(--light     );
}
.bx.bx-menu{
	font-size:  24px;
	margin-top:5px;
	font-weight: bold ;
}

	</style>

	<title>AsumChat - Settings</title>
</head>
<body>


	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx '><img src="img/logo.png" style="width:60px;height:40px;"></i>
			<span class="text">Admin</span>
		</a>
		<ul class="side-menu top">
			<li>
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
			<li>
				<a href="send_request.php" style="text-decoration:none;">
					<i class='bx bx-user-plus' ></i>
					<span class="text">Send Frined Requests</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<li class="active">
				<a href="#" style="text-decoration:none;">
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
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<a href="#" class="notification">
				<i class='bx bxs-bell' ></i>
				<span class="num">8</span>
			</a>
			<a href="#" class="profile">
				<img src="img/people.png">
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Settings</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Settings</a>
						</li>
					</ul>
				</div>
			
			</div>
			<div class="notifications">
			<h2 class="mb-3">General Settings</h2>
			<form method="post" action="settings.php">
				<div class="mb-3">
					<label for="username" class="form-label">Username:</label>
					<input type="text" class="form-control" id="username" name="username" placeholder="Enter your username">
				</div>
	
				<div class="mb-3">
					<label for="email" class="form-label">Email:</label>
					<input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
				</div>
	
				<button type="submit" class="btn btn-primary" name="general-settings">Save General Settings</button>
			</form>
	   
	
		<!-- Security Settings -->
		<div class="settings-section mb-4">
			<h2 class="mb-3">Security Settings</h2>
			<form method="post" action="settings.php">
				<div class="mb-3">
					<label for="password" class="form-label">Password:</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
				</div>
	
				<div class="mb-3">
					<label for="confirm-password" class="form-label">Confirm Password:</label>
					<input type="password" class="form-control" id="confirm-password" name="confirm-password" placeholder="Confirm your password">
				</div>
	
				<button type="submit" class="btn btn-primary" name="security-settings">Save Security Settings</button>
			</form>
		</div>
	
		<!-- Notification Settings -->
		<div class="settings-section">
			<h2 class="mb-3">Notification Settings</h2>
			<form method="post" action="settings.php">
				<div class="mb-3 form-check">
					<input type="checkbox" class="form-check-input" id="enable-notifications" name="enable-notifications">
					<label class="form-check-label" for="enable-notifications">Enable Notifications</label>
				</div>
	
				<button type="submit" class="btn btn-primary" name="notification-settings">Save Notification Settings</button>
			</form>
		</div>
		</div>
		</main>
		<!-- MAIN -->
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