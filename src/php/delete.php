<?php

include ('session.php');      // the session handling functions
include ('sqlfuncs.php');     // common SQL database functions
include ('record.php');       // the forms for display/edit of a record

$alias = 'delete.php';        // avoid hard-coding the script name
$type  = $_GET['type'];       // my edit type as a GET parameter
$uid   = $_GET['uid'];        // my UID (record ID) as a GET parameter
$srow  = $_GET['start_row'];  // the starting row to display when I go back

include('inc/_def_header.html');        // display our standard page layout

/* Validate that I've been called with the required parameters:
**
** I need an edit type so I know the correct state
** I need a UID so I know which record the user is manipulating
** I need a starting row number, so I can go back to the same page
*/
if (!IsSet($type)) die("$alias called without an edit type.");
if (!IsSet($uid))  die("$alias called without a uid (record identifier).");
if (!IsSet($srow)) die("$alias called without a starting row number.");

// Only allow the edit types that I support here
if($type != DELETE_FORM and $type != DELETE_COMMIT){
	print("$alias called with an invalid edit type!<br>\r\n");
     print("program execution cannot continue.<br>\r\n");
	include('inc/_def_footer.html');
	exit();
}

// Let's make sure we can read the record that the user wants to manipulate
$record = db_read_record($uid);

// if we found the record, record['ok'] will be set to true
if (!$record['ok']){
     print("$alias encountered an error reading record [$uid] from the database");
	include('inc/_def_footer.html');
	exit();
}

/*
** The script is called with either DELETE_FORM to display the confirmation
** dialog, or DELETE_COMMIT to actually delete the record. Perform the
** appropriate action based on that.
*/
if($type == DELETE_FORM){
     // display the record in the context of a confirm delete form
	modify_record($alias, 'Delete', $uid, $record, $srow, DELETE_COMMIT, 0);
}
elseif($type == DELETE_COMMIT){

	db_delete_record($alias,$uid);     // perform the delete
	db_count_rows();                   // recount the rows in the database table     

	$including = 'yes';                // so display.php will not do things we already did
	include ('display.php');           // display the primary table
	$title = $record['title'];
	print("Deleting \"$title\" with record ID of $uid ...\r\n<br>");
}

include('inc/_def_footer.html');
?>
