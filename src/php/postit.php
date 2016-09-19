<?php

session_start();

include_once ('cuserlist.php');      // the userlist class

//require('cuserlist.php');
/*
function __autoload($class)
{
    require($class.'.php');
}
*/
// Create a cUserList object to hold the users
$users = new cUserList();

// This include file is where you establish your system users and passwords
include ('users.logininfo.php');

//print_r($_SESSION);
//unset($_SESSION['login_tries']); 	// when I mess up. :)

/*
** The following function is used to print an error message to the user
** when something is going wrong during the login process. It will first
** print the HTML header, then the message, and an HTML footer, so our
** document is well-formed.
*/

function start_section()
{
	?>
    <section class="section">
        <div class="container">
            <div class="login-error">
                <h3>Movie Tracking System Login Page</h3>
	<?php
}

function end_section()
{
	?>
	        </div>
	    </div>
	</section>
	<?php
}

function print_message($msg, $exit=0)
{
	include ('inc/_skinny_header.html');
	
	start_section();
    print ("<h4>Message: $msg</h4>");
    end_section();
    
	include ('inc/_skinny_footer.html');
	if($exit) exit();
}

/*
** First make sure that someone isn't trying to hack into the site. 
** After 3 tries, just lock them out of the system. They will have
** to close this browser and try again in order to keep hacking.
** This isn't the most secure, but it's good enough for this application.
*/

if (!IsSet($_SESSION['login_tries'])){	// this keeps track of how many times they tried to login
    $_SESSION['login_tries'] = 1;
}
elseif ($_SESSION['login_tries'] > 3){	// after 3 times, go ahead and lock out this browser session
    print_message("Sorry, you are locked out because you don't know the password",1);
}

// Load the user and password from the index.html form into local variables
$user = strtolower($_POST['username']);
$pass = $_POST['password'];

if (!IsSet($user)){					// check to see if a username was entered
    print_message("A user name is required, please press BACK and try again!",1);
}
elseif (!$users->validUser($user)){
    print_message("Unknown user, please press back and try again!",1);
}
elseif (!$users->validPassword($pass)){
    $_SESSION['login_tries'] += 1;
    print_message("Unknown password, please press back and try again!",1);
}

unset($_SESSION['login_tries']); 	// reset in case they want to relogin as a different user
$_SESSION['logged_in'] = 1;			// set a flag in our session data so we know this session is logged in

// The admin can do several db functions via the admin menu...
if ($users->isAdmin()){
	$_SESSION['isadmin'] = 1;		// these users are admins.
}
else {
	$_SESSION['isadmin'] = NULL;	// always clear it so it doesn't stick ...
}

// The Guest user cannot write anything.
if ($user == 'guest'){
	$_SESSION['isguest'] = 1;		// these users are guests
}
else {
	$_SESSION['isguest'] = NULL;	// always clear it so it doesn't stick ...
}

// Remember this user exactly how they typed it in the login box
$_SESSION['username'] = $_POST['username'];

// Go ahead and initialize the session variables and SQL functions and some SQL defaults
include ('session.php');
include ('sqlfuncs.php');
db_set_query(NULL);			// set the query to find ALL
db_count_rows();			// get a count so the display page knows how many records we have

// dump the default page information now.
include ('inc/_def_header.html');		// the default header
include ('inc/_mainbody.html');	// the default body content
include ('inc/_def_footer.html');		// the default footer
?>
