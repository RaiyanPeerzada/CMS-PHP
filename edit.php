<!--
 * 
 * Script: edit.php
 * Provides a form where the user can edit a specific post title and contents.
 * Author: Raiyan Peerzada
 * Version: 1.0
 * Date Created: 27.09.2018
 * Last Updated: 27.09.2018
 *
 * -->

<?php

//require ('authenticate.php');
session_start();

    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        header("location: index.php");
    }
    require('connect.php');
    
    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: login.php');
    }
    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        header("location: index.php");
    }


$ModelID = filter_input(INPUT_GET, 'ModelID', FILTER_SANITIZE_NUMBER_INT);

$select_query = 'SELECT ModelID, ModelMake, ModelName, ModelYear, ModelImage, CreatedBy, CreatedDateTime FROM carlist WHERE ModelID = :ModelID';

$statement = $db->prepare($select_query);
$statement->bindValue(':ModelID', $ModelID, PDO::PARAM_INT);
$statement->execute();

$car = [];

$car = $statement->fetchAll();

$select_query1 = 'SELECT SpecID, EngineType, Displacement, Torque, Speed, ModelID FROM carspecs WHERE ModelID = :ModelID';

$statement1 = $db->prepare($select_query1);
$statement1->bindValue(':ModelID', $ModelID, PDO::PARAM_INT);
$statement1->execute();

$carspec = [];

$carspec = $statement1->fetch();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Car categories</title>
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

<form action="process_post.php" method="post" enctype="multipart/form-data">
    <fieldset>
      <legend>Edit Car Post</legend>

      <div class="formfield">
        <label for="make" class="control-label col-sm-2">Model Make</label>
        <input type="text" name="make" id="make" value="<?= $car[0]['ModelMake'] ?>" />
      </div>
     <div class="formfield">
        <label for="modelname" class="control-label col-sm-2">Model Name</label>
        <input type="text" name="modelname" id="modelname" 
        value="<?= $car[0]['ModelName'] ?>" />
      </div>
      <div class="formfield">
	       <label for="year" class="control-label col-sm-2">Model Year</label>
	       <select id="year" name = "year">
	       		<option value="<?= $car[0]['ModelYear'] ?>"><?= $car[0]['ModelYear'] ?></option>
	            <option value="2019">2019</option>
	        	<option value="2018">2018</option>
	        	<option value="2017">2017</option>
	        	<option value="2016">2016</option>
	        	<option value="2015">2015</option>
	        	<option value="2014">2014</option>
	        	<option value="2013">2013</option>
	        </select>
      </div>
      <div class="formfield">
        <label for="image" class="control-label col-sm-2">Image Filename:</label>

        <?php if ($car[0]['ModelImage'] == 'null.jpg') :?>

            <input type="file" name="image" id="image">
        <?php else :?>

        <input type="text" name="make" id="make" value="<?= $car[0]['ModelImage'] ?>" disabled>
        <input type="checkbox" name="imagedel" value="imagedel"> Delete Image 

      <?php endif ?>

      </div>
      </fieldset>

      <fieldset>
      <legend>Car Specifications</legend>

      <div class="formfield">
        <label for="engine" class="control-label col-sm-2">Engine Type</label>
        <input type="text" name="engine" id="engine" value="<?= $carspec['EngineType'] ?>" />
      </div>
      <div class="formfield">
        <label for="disp" class="control-label col-sm-2">Displacement(cm3)</label>
        <input type="number" name="disp" id="disp" value="<?= $carspec['Displacement'] ?>"/>
      </div>
      <div class="formfield">
        <label for="torque" class="control-label col-sm-2">Torque(RPM)</label>
        <input type="number" name="torque" id="torque" value="<?= $carspec['Torque'] ?>"/>
      </div>
      <div class="formfield">
        <label for="speed" class="control-label col-sm-2">Speed(km/hr)</label>
        <input type="number" name="speed" id="speed" value="<?= $carspec['Speed'] ?>" />
      </div>

      <!-- Slug input -->
      <div class="formfield">
        <label for="slug" class="control-label col-sm-2">URL Permalinks</label>
        <input type="text" name="slug" id="slug" placeholder="ModelMake-ModelName" />
      </div>

      <div class="formfield">
         <input type="hidden" name="ModelID" value="<?= $car[0]['ModelID'] ?>" />
         <input type="hidden" name="SpecID" value="<?= $carspec['SpecID'] ?>" />
        <input type="submit" name="command" value="Update" />
        <input type="submit" name="command" value="Delete" onclick="return confirm('Are you sure you wish to delete this post?')" />
      </div>
    </fieldset>
  </form>

</div>

  <div class="footer">
		<div class="copyright">
			&copy; Copywrong 2018 - No Rights Reserved
		</div>
		<div class="atisda">
			<a> Designed by RRC Designers</a> 
	</div>
</div>

</body>
</html>