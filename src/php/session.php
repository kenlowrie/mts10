<?php
/*
** This file is included by all other PHP scripts. It makes sure that you are
** logged in, and if not, it displays a message and exits. It also defines the
** various constants used by this application, to avoid hard-coding things in
** the application.
*/
session_start();

define(ROWS_TO_DISPLAY,22);        // how many rows are displayed at one time
define(EDIT_FORM,1);               // type used to specify display an edit form
define(EDIT_COMMIT,2);             // type used to specify commit changes to record
define(DELETE_FORM,3);             // type used to specify display a delete form
define(DELETE_COMMIT,4);           // type used to specify commit a delete record     
define(ADD_FORM,5);                // type used to specify display an add form
define(ADD_COMMIT,6);              // type used to specify commit an add record
define(ADD_FORCE,7);               // type used to specify commit a duplicate add record

$logged_in = $_SESSION['logged_in'];	// This is my session variable that tells if I have logged in

if (!IsSet($logged_in)){
    echo "You must be logged in to use this application";
	exit();
}

/*
** A function that will tell you whether or not the currently logged in user is an administrator.
*/
function is_admin()
{
	$isadmin = $_SESSION['isadmin'];
	if (IsSet($isadmin)){
		return 1;
	}
	return 0;
}

function is_guest()
{
	$isguest = $_SESSION['isguest'];
	if (IsSet($isguest)){
		return 1;
	}
	return 0;
}
?>
