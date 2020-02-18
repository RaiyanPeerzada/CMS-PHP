<!--
 * 
 * Script: index.php
 * Caropedia Home Page - Displays car posts to all users and
 * allows authenticated users to make modifications
 * Author: Raiyan Peerzada
 * Version: 1.0
 * Date Created: 02.11.2018
 * Last Updated: 02.11.2018
 *
 * -->
<?php 
 session_start(); 

    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        header("location: index.php");
    }
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>CarOpedia CMS</title>
<link rel="stylesheet" type="text/css" href="source/bootstrap-3.3.6-dist/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="source/font-awesome-4.5.0/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="style/slider.css">
	<link rel="stylesheet" type="text/css" href="style/mystyle.css">
</head>
<body>

<!-- Header -->
<div class="allcontain">
	<div class="header">
			<ul class="socialicon">
				<li><a href="#"><i class="fa fa-facebook"></i></a></li>
				<li><a href="#"><i class="fa fa-twitter"></i></a></li>
				<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
				<li><a href="#"><i class="fa fa-pinterest"></i></a></li>
			</ul>
			<ul class="givusacall">
				<li>Give us a call : +66666666 </li>
			</ul>
			<ul class="logreg">
				<!-- <li><a href="login.php">Login </a> </li>
				<li><a href="register.php"><span class="register">Register</span></a></li> -->
				<?php  if (isset($_SESSION['username'])) : ?>
                        <li><a href="index.php">Welcome <strong><?=$_SESSION['username']?></strong>, <?=$_SESSION['success']?></a></li>
                        <li><a href="index.php?logout='1'" style="color: red;">logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login </a> </li>
						<li><a href="register.php"><span class="register">Register</span></a></li>
                    <?php endif ?>
			</ul>
	</div>
	<!-- Navbar Up -->
	<nav class="topnavbar navbar-default topnav">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand logo" href="#"><img src="image/logo1.png" alt="logo"></a>
			</div>	 
		</div>
		<div class="collapse navbar-collapse" id="upmenu">
			<ul class="nav navbar-nav" id="navbarontop">
				<li class="active"><a href="index.php">HOME</a> </li>
				<li class="dropdown">
					<a href="categories.php">CATEGORIES</a>
				</li>
				<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">BLOGS <span class="caret"></span></a>
						<ul class="dropdown-menu dropdowncostume">
							<li><a href="https://mercedesblog.com/">Mercedes</a></li>
							<li><a href="https://www.audiboost.com/content.php">Audi</a></li>
							<li><a href="https://www.bmwblog.com/">BMW</a></li>
						</ul>
				</li>
				<li>
					<a href="contactUS.html">CONTACT</a>
				</li>
				<li>
					<a href="create.php"><span class="postnewcar">POST NEW CAR</span></a>
				</li>
			</ul>
		</div>
	</nav>
</div>

<!--_______________________________________ Carousel__________________________________ -->
<div class="allcontain">
	<div id="carousel-up" class="carousel slide" data-ride="carousel">
		<div class="carousel-inner " role="listbox">
			<div class="item active">
				<img src="image/oldcar.jpg" alt="oldcar">
				<div class="carousel-caption">
					<h2>Porsche 356</h2>
				</div>
			</div>
			<div class="item">
				<img src="image/porche.jpg" alt="porche">
				<div class="carousel-caption">
					<h2>Porsche</h2>
				</div>
			</div>
			<div class="item">
				<img src="image/benz.jpg" alt="benz">
				<div class="carousel-caption">
					<h2>Porsche</h2>
				</div>
			</div>
		</div>
		
	</div>
</div>

<!-- ____________________Featured Section ______________________________--> 

<h1 class="text-center">About US</h1>

<div>
	<p class="text-center" style="margin-bottom: 50px">
		Caropedia is a non-profit organisation that will provide detailed information about cars available in the market. It will cover all the basic information including the year, make, model, trim, and other specifications. It will also provide latest updates and news for all those gearheads out there. Caropedia will also have an ability to provide custom search for its users. 
	</p>
</div>

<div class="footer">
		<div class="copyright">
			&copy; Copy right 2016 | <a href="#">Privacy </a>| <a href="#">Policy</a>
		</div>
		<div class="atisda">
			<a> Designed by Web Domus Italia - Web Agency </a> 
	</div>
</div>


<!-- js -->
<script src="source/bootstrap-3.3.6-dist/js/jquery.js"></script>
<script src="source/bootstrap-3.3.6-dist/js/jquery.1.11.js"></script>
<script src="source/bootstrap-3.3.6-dist/js/bootstrap.js"></script>

</body>
</html>