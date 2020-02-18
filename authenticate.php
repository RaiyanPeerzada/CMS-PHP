<!--
 * 
 * Script: authenticate.php
 * To authenticate users
 * Author: Raiyan Peerzada
 * Version: 1.0
 * Date Created: 27.09.2018
 * Last Updated: 27.09.2018
 *
 * -->

 <?php

  define('ADMIN_LOGIN','wally');

  define('ADMIN_PASSWORD','mypass');


  if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])

      || ($_SERVER['PHP_AUTH_USER'] != ADMIN_LOGIN)

      || ($_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD)) {

    header('HTTP/1.1 401 Unauthorized');

    header('WWW-Authenticate: Basic realm="Our Blog"');

    exit("Access Denied: Username and password required.");

  }

?>