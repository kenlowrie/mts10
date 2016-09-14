<?php

include ('session.php');      // the session handling functions
include ('sqlfuncs.php');     // common SQL database functions
include ('record.php');       // the forms for display/edit of a record

$alias = 'update.php';        // avoid hard-coding the script name
$type  = $_GET['type'];       // my edit type as a GET parameter
$uid   = $_GET['uid'];        // my UID (record ID) as a GET parameter
$srow  = $_GET['start_row'];  // the starting row to display when I go back

include('header.inc');        // display our standard page layout

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
if($type != EDIT_FORM and $type != EDIT_COMMIT){
	print("$alias called with an invalid edit type!<br>\r\n");
     print("program execution cannot continue.<br>\r\n");
	include('footer.inc');
	exit();
}

// Let's make sure we can read the record that the user wants to manipulate
$record = db_read_record($uid);

// if we found the record, record['ok'] will be set to true
if (!$record['ok']){
     print("$alias encountered an error reading record [$uid] from the database");
	include('footer.inc');
	exit();
}

/*
** The script is called with either EDIT_FORM to display an EDIT record
** dialog, or EDIT_COMMIT to actually update the record. Perform the
** appropriate action based on that.
*/
if($type == EDIT_FORM){
     // display the record in the context of an edit form
	modify_record($alias, 'Update', $uid, $record, $srow, EDIT_COMMIT);
}
elseif($type == EDIT_COMMIT){

	$record['title']  = $_POST['title'];    // transfer the form variables into
	$record['format'] = $_POST['format'];   // the record array which the database
	$record['genre']  = $_POST['genre'];    // functions are familiar with.
	$record['rating'] = $_POST['rating'];
	$record['loanee'] = $_POST['loanee'];
	$record['imdbid'] = $_POST['imdbid'];
	
	db_write_record($alias,$uid,$record);   // update the record
	$including = 'yes';                     // so display.php will not do things we already did
	include ('display.php');                // display the primary table
	$title = $record['title'];
	print("Updating \"$title\" with record ID of $uid ...\r\n<br>");
}

include('footer.inc');
?>
