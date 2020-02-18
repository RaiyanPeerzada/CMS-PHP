<!--
 * 
 * Script: show.php
 * Displays a full content of the blog.
 * Author: Raiyan Peerzada
 * Version: 1.0
 * Date Created: 27.09.2018
 * Last Updated: 27.09.2018
 *-->


<?php  

require ('connect.php');

$ModelID = filter_input(INPUT_GET, 'ModelID', FILTER_SANITIZE_NUMBER_INT);
$slug = filter_input(INPUT_GET, 'slug',  FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$select_query = 'SELECT SpecID, EngineType, Displacement, Torque, Speed, ModelID FROM carspecs WHERE ModelID = :ModelID';

$statement = $db->prepare($select_query);
$statement->bindValue(':ModelID', $ModelID, PDO::PARAM_INT);
$statement->execute();

$check_query = 'SELECT ModelID, ModelMake, ModelName, ModelYear, ModelImage, CreatedBy, CreatedDateTime, slugtext FROM carlist WHERE ModelID = :ModelID AND slugtext = :slug';

$state = $db->prepare($check_query);
$state->bindValue(':ModelID', $ModelID, PDO::PARAM_INT);
$state->bindValue(':slug', $slug);
$state->execute();


$singlecar = [];
$singlecar = $statement->fetch();

$car =[];
$car= $state->fetch();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Car description</title>
	<link rel="stylesheet" type="text/css" href="source/bootstrap-3.3.6-dist/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="source/font-awesome-4.5.0/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="style/slider.css">
	<link rel="stylesheet" type="text/css" href="style/mystyle.css">
	<link rel="stylesheet" type="text/css" href="style.css">


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
				<li><a href="edit.php?ModelID=<?= $ModelID ?>">Edit </a> </li>
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
					<a href="#">CONTACT</a>
				</li>
				<li>
					<a href="create.php"><span class="postnewcar">POST NEW CAR</span></a>
				</li>
			</ul>
		</div>
	</nav>
</div>

<div id="wrapper">

	<?php if ($ModelID == $car['ModelID'] && $slug == $car['slugtext']) :?>

	<h1>Specifications</h1>
	<ul>
		<li>
			<p>Engine: <?= $singlecar['EngineType'] ?></p>
		</li>

		<li>
			<p>Displacement: <?= $singlecar['Displacement'] ?> cm3</p>
		</li>

		<li>
			<p>Torque: <?= $singlecar['Torque'] ?> RPM</p>
		</li>

		<li>
			<p>Speed: <?= $singlecar['Speed'] ?> km/hr</p>
		</li>		
	</ul>
	<?php else :?>
			<h1>OMG!!! It's an invalid URL</h1>
	<?php endif ?>


</div>


<div class="footer" style="margin-top: 405px;">
		<div class="copyright">
			&copy; Copywrong 2018 - No Rights Reserved
		</div>
		<div class="atisda">
			<a> Designed by RRC Designers</a> 
	</div>
</div>

</body>
</html>