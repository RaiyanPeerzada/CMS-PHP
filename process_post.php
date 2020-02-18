<!--
 * 
 * Script: process_post.php
 * Processes the data from previous form input
 * Update/Modify database using sanitized data
 * Displays error message if any validation fails
 * Author: Raiyan Peerzada
 * Version: 1.0
 * Date Created: 27.09.2018
 * Last Updated: 27.09.2018
 *
 * -->

<?php  

include 'php-image-resize-master\lib\ImageResize.php';
use \Gumlet\ImageResize;

$img = "";

function file_upload_path ($original_filename, $upload_subfolder_name = 'images')
{
    $current_folder = dirname(__FILE__);
    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
    return join(DIRECTORY_SEPARATOR, $path_segments);
}

function file_is_an_image($temporary_path, $new_path) 
 {
        $allowed_mime_types      = ['image/jpeg', 'image/png'];
        $allowed_file_extensions = ['jpg', 'jpeg', 'png'];
        
        $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
        $actual_mime_type        = $_FILES['image']['type'];
        

        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
        $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
        
        $fileExtCheck = $file_extension_is_valid? "true" : "false";
        $fileExtCheck1 = $mime_type_is_valid? "true" : "false";

        return $file_extension_is_valid && $mime_type_is_valid;
}

$image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
$upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);

    if ($image_upload_detected) { 
        
        $image_filename = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
        $image_fileExtention = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $temporary_image_path = $_FILES['image']['tmp_name'];

        $new_image_path = file_upload_path($_FILES['image']['name']);
 
        if (file_is_an_image($temporary_image_path, $new_image_path)) {


            move_uploaded_file($temporary_image_path, $new_image_path);

            $image = new ImageResize(file_upload_path( $image_filename . "." . $image_fileExtention));
            $image->resize(90,60);
            $image->save(file_upload_path( $image_filename . "." . $image_fileExtention));

            
            $img = $_FILES['image']['name'];
        }
    }

$isValid = false;


// Sanitize user input to escape HTML entities and filter out dangerous characters.

$make        = filter_input(INPUT_POST, 'make', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$modelname   = filter_input(INPUT_POST, 'modelname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$year        = $_POST['year'];
$engine      = filter_input(INPUT_POST, 'engine', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$disp        = filter_input(INPUT_POST, 'disp', FILTER_SANITIZE_NUMBER_INT);
$torque      = filter_input(INPUT_POST, 'torque', FILTER_SANITIZE_NUMBER_INT);
$speed       = filter_input(INPUT_POST, 'speed', FILTER_SANITIZE_NUMBER_INT);

$ModelID     = filter_input(INPUT_POST, 'ModelID', FILTER_SANITIZE_NUMBER_INT);
$SpecID      = filter_input(INPUT_POST, 'SpecID', FILTER_SANITIZE_NUMBER_INT);
$slug        = filter_input(INPUT_POST, 'slug', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$slugReg     = preg_replace('#[ -]+#', '-', $slug);

function insert_data($make, $modelname, $year, $engine, $disp, $torque, $speed, $img, $slugReg)   
{
    require ('connect.php');
    
    //Build the parameterized SQL query and bind to the above sanitized values.
    $query     = "INSERT INTO `carlist` (`ModelID`, `ModelMake`, `ModelName`, `ModelYear`, `ModelImage`, `CreatedBy`, `CreatedDateTime`, `slugtext` ) VALUES( NULL, :make, :modelname, :year, :image, 'Dbadmin', NOW(), :slug)";

    $statement = $db->prepare($query);
    $statement->bindValue(':make', $make);        
    $statement->bindValue(':modelname', $modelname);
    $statement->bindValue(':year', $year);
    $statement->bindValue(':image',$img);
    $statement->bindValue(':slug',$slugReg);
   // $statement->bindValue(':user',  $_SESSION['username']);

    // // Execute the INSERT.
    $statement->execute();

    // // Determine the primary key of the inserted row.
    $insert_id = $db->lastInsertId();

    // Update the car spec details
    $specquery     = "INSERT INTO `carspecs` (`EngineType`, `Displacement`, `Torque`, `Speed`, `ModelID`) VALUES (:engine, :disp, :torque, :speed, :insert_id)";

    $state = $db->prepare($specquery);
    $state->bindValue(':engine', $engine);        
    $state->bindValue(':disp', $disp);
    $state->bindValue(':torque', $torque);
    $state->bindValue(':speed', $speed);
    $state->bindValue(':insert_id', $insert_id);

    // // Execute the INSERT.
    $state->execute();
} 



function update_data($ModelID, $SpecID, $make, $modelname, $year, $engine, $disp, $torque, $speed, $img, $slugReg)      
{
    require ('connect.php');

    //Build the parameterized SQL query and bind to the above sanitized values.
    $query     = "UPDATE carlist SET ModelMake = :make, ModelName = :modelname, ModelYear = :year, ModelImage = :image, slugtext = :slug WHERE ModelID = :ModelID ";;
    $statement = $db->prepare($query);
    $statement->bindValue(':make', $make);        
    $statement->bindValue(':modelname', $modelname);
    $statement->bindValue(':year', $year);
    $statement->bindValue(':image', $img);
    $statement->bindValue(':slug', $slugReg);
    $statement->bindValue(':ModelID', $ModelID, PDO::PARAM_INT);

    // // Execute the UPDATE.
    $statement->execute();

    //Delete image if the box is checked
    if (isset($_POST['imagedel'])) {


        $imgquery = "UPDATE carlist SET ModelImage = :nullImage WHERE ModelID = :ModelID ";;

        $stat = $db->prepare($imgquery);
        $stat->bindValue(':nullImage', 'null.jpg');
        $stat->bindValue(':ModelID', $ModelID, PDO::PARAM_INT);
        $stat->execute();
    }

    $specquery     = "UPDATE carspecs SET EngineType = :engine, Displacement = :disp, Torque = :torque, Speed = :speed WHERE SpecID = :SpecID ";

    $state = $db->prepare($specquery);
    $state->bindValue(':engine', $engine);        
    $state->bindValue(':disp', $disp);
    $state->bindValue(':torque', $torque);
    $state->bindValue(':speed', $speed);
    $state->bindValue(':SpecID', $SpecID, PDO::PARAM_INT);

    // // Execute the UPDATE.
    $state->execute();
}  

function delete_data($ModelID, $SpecID)    
{
    require ('connect.php');
    // Sanitize user input to escape HTML entities and filter out dangerous characters.
    $ModelID = filter_input(INPUT_POST, 'ModelID', FILTER_SANITIZE_NUMBER_INT);
    $SpecID = filter_input(INPUT_POST, 'SpecID', FILTER_SANITIZE_NUMBER_INT);
    
    //Build the parameterized SQL query and bind to the above sanitized values.
    $query = "DELETE FROM carlist WHERE ModelID = :ModelID";
    $statement = $db->prepare($query);
    $statement->bindValue(':ModelID', $ModelID, PDO::PARAM_INT);

    // // Execute the INSERT.
    $statement->execute();

    $specquery = "DELETE FROM carspecs WHERE SpecID = :SpecID";
    $state = $db->prepare($specquery);
    $state->bindValue(':SpecID', $SpecID, PDO::PARAM_INT);

    // // Execute the INSERT.
    $state->execute();
}


//Check if not empty, make changes to the database
if (! empty($_POST['make']) && ! empty($_POST['modelname']) && ! empty($_POST['year']) && ! empty($_POST['engine'])) 
{
     $isValid = true;

     if ( $_POST['command'] === 'Create') {   
        insert_data($make, $modelname, $year, $engine, $disp, $torque, $speed, $img, $slugReg) ;
        echo "create";
     }

    if ( $_POST['command'] === 'Update') {   
        update_data($ModelID, $SpecID, $make, $modelname, $year, $engine, $disp, $torque, $speed, $img, $slugReg) ;
        echo "update";
     }

     header("Location: categories.php");             
} 

else {  
   $isValid = false;
};

// Delete post
if ( $_POST['command'] === 'Delete') 
{ 
        $isValid = true;   
        delete_data($ModelID, $SpecID) ;
        header("categories: index.php");
     } ;


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
    <link rel="stylesheet" type="text/css" href="styles.css">
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
                <li><a href="#">Login </a> </li>
                <li><a href="#"><span class="register">Register</span></a></li>
            </ul>
    </div>
    <!-- Navbar Up -->
    <nav class="topnavbar navbar-default topnav">
        <div class="container">

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

    <?php if (! $isValid) :?>

        <h1>An error occured while processing your post.</h1>
        <p> Inputs must be at least one character.  </p>
        <a href="index.php">Return Home</a> 
        
    <?php endif ?>

</body>
</html>