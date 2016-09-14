<?php
/*
** This page is invoked when the user clicks on logout. It will reset the "logged_in"
** session variable, and then display the main login page. Once the sesson variable
** has been reset, you cannot do anything until you log back in.
*/

session_start();
//TODO: Write a function that removes all the session vars used by this app and call it at exit...
unset($_SESSION['columns_displayed']);
//session_destroy();      //TODO: Not sure if this is correct yet, but it allowed me to clear all the crap... Research...

$logged_in = $_SESSION['logged_in'];
if (IsSet($logged_in)){
    unset($_SESSION['logged_in']); 	// If reloading from main page, force a new login
}
include ('index.html');				// display the plain old index page
?>
