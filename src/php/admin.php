<?php
/*
** Form with radio buttons so user can select the operation they want to perform.
*/

function draw_radio_admin($value,$help)
{
	print("<div class=\"radio\">\r\n");
	print("    <label><input type=\"radio\" name=\"operation\" value=\"$value\">$help</label>\r\n");
	print("</div>\r\n");
}

/*
** This prints an input button in the form
*/
function draw_button_admin($name,$button_label)
{
    print("<button type=\"submit\" name=\"$name\" value=\"$button_label\" class=\"btn btn-default adminbutton\">$button_label</button>\r\n");
}

function draw_menu()
{
	print("<form class=\"adminform\" method=\"POST\" action=\"admin.php?type=38\">");
	draw_radio_admin("add", "Add 'info' table to database");
	draw_radio_admin("load","Load 'info' table from 'movies-in.txt'");
	draw_radio_admin("dump","Dump 'info' table to 'movies-out.txt'");
	draw_radio_admin("drop","Drop 'info' table from database");
	print("    <div class=\"adminbutton\">\r\n");
	    draw_button_admin("button","Execute Admin Command");
	print("    </div>\r\n");
	print("</form>\r\n");
}

$whoami = 'admin.php';		// Use this in messages so we know where we are

include ('session.php');	// Make sure we are logged in
include ('sqlfuncs.php');	// SQL functions

include('inc/_def_header.html');		// Draw the standard page layout

print("<div class=\"admin\">\r\n");

/*
** Make sure that an adminstrator is currently logged in, otherwise display an error message and stop!
*/
if (!is_admin()){
    print("<div class=\"error\">\r\n");
	print("<h3>MTS Administrative Page Error</h3>");
	print("<h4>$whoami: you must be an administrator to run this script.</h4>\r\n");
	print("</div>\r\n");
	include ('inc/_def_footer.html');
	exit();
}


print("<h1>Admin Options</h1>\r\n");	// Ok, we're good to go, display the page title

/*
** This page invokes itself with a GET parameter on the URL of 38, and then a POST
** parameter indicating the radio button chosen in the form displayed by draw_menu().
** Parse that out, and then do the action that has been requested.
*/
print("<div class=\"postmessage\">\r\n");

$type = $_GET['type'];

if (IsSet($type) and $type == 38){
	$which = $_POST['operation'];
	
	switch( $which )
	{
		case 'add':
			print "$whoami: Adding table to database...<br>";
			db_create_table();		// create the database table
			break;

		case 'drop':
			print "$whoami: Dropping table from database...<br>";
			db_drop_table();		// drop the database table
			break;
			
		case 'load':
			print "$whoami: Loading data from from flat file 'movies-in.txt' ...<br>";
			db_load_from_file();	// load data from a comma delimited file
			break;
		
		case 'dump':
			print "$whoami: Dumping data to flat file 'movies-out.txt' ...<br>";
			db_dump_to_file();		// dump data to a comma delimited file
			break;
		
		default:
			// This is likely caused if the user attempts to form the URL or
			// if the administrative menu has been updated but this switch
			// statement has not. :)
			print 'Unknown operation type passed. No action taken.';
			break;
	}
}

print("</div>\r\n");

/*
** Draw the menu for the admin options
*/
draw_menu();

print("</div>\r\n");

include('inc/_def_footer.html');		// draw the standard footer
?>
