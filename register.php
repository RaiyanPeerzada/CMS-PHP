<?php 

require("lib/simple-botdetect.php");
include('server.php');


?>
<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <link rel="stylesheet" type="text/css" href="loginstyle.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src='captcha.js'></script>
    <style type="text/css">
        .username-available-msg, .username-taken-msg {
            display: none;
        }
    </style>

    
</head>
<body>
    <div class="header">
        <h2>Register</h2>
    </div>

    <form class="reglog" method="post" action="register.php">

        <?php include('errors.php'); ?>
        <div class="username-available-msg alert alert-primary input-group" role="alert" style="color:black">
            Username is available.
        </div>
        <div class="username-taken-msg alert alert-danger input-group" role="alert"  style="color:black">
            Sorry this username is taken.
        </div>
        <div class="input-group">
            <label for="inputUsername" id="regLabel">Username</label>
            <input id="inputUsername" type="text" name="username" value="<?php echo $username; ?>">
        </div>
        <div class="input-group">
            <label id="regLabel">Email</label>
            <input type="email" name="email" value="<?php echo $email; ?>">
        </div>
        <div class="input-group">
            <label id="regLabel">Password</label>
            <input type="password" name="password_1">
        </div>
        <div class="input-group">
            <label id="regLabel">Confirm password</label>
            <input type="password" name="password_2">
        </div>

       <label for="CaptchaCode">Retype the characters from the picture:
		</label>

		<?php // Adding BotDetect Captcha to the page 
		  
		  $ExampleCaptcha = new SimpleCaptcha("ExampleCaptcha");
		  echo $ExampleCaptcha->Html(); 
		?>

		<input name="CaptchaCode" id="CaptchaCode" type="text" />

        <div class="input-group">
            <button type="submit" class="btn" name="reg_user">Register</button>
        </div>
        <p id="regLabel">
            Already a member? <a href="login.php">Sign in</a>
        </p>
    </form>

        <script src="main.js"></script>
    
</body>
</html>