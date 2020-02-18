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

require ('connect.php');


// $select_query = 'SELECT ModelID, ModelMake, ModelName, ModelYear, ModelImage, CreatedBy, CreatedDateTime FROM carlist ORDER BY CreatedDateTime';

// $statement = $db->prepare($select_query);
// $statement->execute();

// $cars = [];

// $cars = $statement->fetchAll();

//Replacement for search query
	$select_query = '';
    $statement = '';
    $status = '';

    $rowperpage = 5;
    $row = 0;
    $enablePageLink = "none";

    if (isset($_GET['searchResult'])) {

    	//paginated
    	// Previous Button
        if(isset($_POST['but_prev'])){
            $row = $_POST['row'];
            $row -= $rowperpage;
            if( $row < 0 ){
                $row = 0;
            }
        }

        // Next Button
        if(isset($_POST['but_next'])){
            $row = $_POST['row'];
            $allcount = $_POST['allcount'];
            $val = $row + $rowperpage;
            if( $val < $allcount ){
                $row = $val;
            }
        }

        $searchString = "%" . $_GET['searchResult'] . "%";

        $count_query = "SELECT COUNT(*) AS rowCount FROM carlist WHERE ModelMake LIKE :search OR ModelName LIKE :search";
        $statement = $db->prepare($count_query);
        $statement->bindValue(':search', $searchString);      
        $statement->execute();
        $status = $statement->fetch();
        $allcount = $status['rowCount'];

        $enablePageLink = ($allcount > 5)? "inline-block" : "none";
        $select_query = "SELECT * FROM carlist WHERE ModelMake LIKE :search OR ModelName LIKE :search LIMIT $row,".$rowperpage;

        $statement = $db->prepare($select_query);
        $statement->bindValue(':search', $searchString);
    }

    else{
    	
        $select_query = "SELECT ModelID, ModelMake, ModelName, ModelYear, ModelImage, CreatedBy, CreatedDateTime, slugtext FROM carlist ORDER BY CreatedDateTime";
        $statement = $db->prepare($select_query);
    }

    $statement->execute();
    //$status = $statement->fetchAll();

    $cars = [];
	$cars = $statement->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Car categories</title>
	<link rel="stylesheet" type="text/css" href="source/bootstrap-3.3.6-dist/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="source/font-awesome-4.5.0/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="style/slider.css">
	<link rel="stylesheet" type="text/css" href="style/mystyle.css">
	<link rel="stylesheet" type="text/css" href="sort.css">

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
			<div class="logreg">
				   <form method=post action="searchProcess.php">
                        <p><input name="search_text" id="search_text" style="color:black" placeholder="search modelmake"></p>
                        <p><input type="submit" name="search" style="color:black" value="Search" /></p>
                    </form>
			</div>
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

	<div class="feturedsection">
		<h1 class="text-center"><span class="bdots">&bullet;</span>F E A T U R E S<span class="carstxt">C A R S</span>&bullet;</h1>
	</div>

	  <table class="table table-hover sortable"  id="sort" style="margin-left:75px;width:1365px;">
        <thead>
            <tr>
            	<th>#</th>
            	<th onclick="sortTable(1)">Model Make <i class="NameUp"></i><i class="NameDown"></i></th>
                
                <th onclick="sortTable(2)">Year<i class="YearUp"></i><i class="YearDown"></i></th>  
                <th> Model </th>
            </tr>
            </thead>

            <?php foreach($cars as $car) :?>
                <tbody>
                    <tr>
                      <td>
                      	<img src="images/<?= $car['ModelImage'] ?>" alt="<?= $car['ModelImage'] ?>">
                      </td>
                      <td><?=$car['ModelMake']?></td>
                      <td><?=$car['ModelYear']?></td>
                      <td><a href="show.php?ModelID=<?= $car['ModelID']?>&slug=<?=$car['slugtext'] ?>"><?=$car['ModelName']?></a></td>   
                    </tr>
                </tbody>
            <?php endforeach ?>
        
     </table>

     <form method="post" action="#" style="display:<?=$enablePageLink?>">
            <div id="div_pagination" style="color:black">
                <input type="hidden" name="row" value="<?php echo $row; ?>">
                <input type="hidden" name="allcount" value="<?php echo $allcount; ?>">
                <input type="submit" class="button" name="but_prev" value="Previous">
                <input type="submit" class="button" name="but_next" value="Next">
            </div>
        </form>

  <div class="footer">
		<div class="copyright">
			&copy; Copywrong 2018 - No Rights Reserved
		</div>
		<div class="atisda">
			<a> Designed by RRC Designers</a> 
	</div>
</div>

<script src="test.js"></script>
<!-- js -->
<script src="source/bootstrap-3.3.6-dist/js/jquery.js"></script>
<script src="source/bootstrap-3.3.6-dist/js/jquery.1.11.js"></script>
<script src="source/bootstrap-3.3.6-dist/js/bootstrap.js"></script>

</body>
</html>