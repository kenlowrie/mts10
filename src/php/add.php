<?php

include ('session.php');      // the session handling functions
include ('sqlfuncs.php');     // common SQL database functions
include ('record.php');       // the forms for display/edit of a record

$alias = 'add.php';           // avoid hard-coding the script name
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
if($type != ADD_FORM and $type != ADD_COMMIT and $type != ADD_FORCE){
	print("$alias called with an invalid edit type!<br>\r\n");
     print("program execution cannot continue.<br>\r\n");
	include('footer.inc');
	exit();
}

/*
** The script is called with either ADD_FORM to display an empty ADD record
** dialog, or ADD_COMMIT to actually add the record. Perform the
** appropriate action based on that.
*/
if($type == ADD_FORM){
     // Display the ADD record form
	$record['title']  = 'Title';       // setup a record array like the
	$record['format'] = 'WS';          // modify_record() function likes
	$record['genre']  = 'Action';
	$record['rating'] = 'PG13';
	$record['loanee'] = '';
	$record['imdbid'] = '';
	
     // display the form so the user can fill it out and submit
	modify_record($alias, 'Create', $uid, $record, $srow, ADD_COMMIT);
}
elseif($type == ADD_COMMIT or $type == ADD_FORCE){
     // The user wants to add the record
	$record['title']  = $_POST['title'];    // transfer the form variables into
	$record['format'] = $_POST['format'];   // the record array which the database
	$record['genre']  = $_POST['genre'];    // functions are familiar with.
	$record['rating'] = $_POST['rating'];
	$record['loanee'] = $_POST['loanee'];
	$record['imdbid'] = $_POST['imdbid'];

     $add_now = 1;       // Assume that we are going to add the record

     if( $type == ADD_COMMIT )
     {
          /*
          ** Check to see if the title exists already
          */
          if (db_lookup_record($record['title']) == 1)
          {
               print ("<h3>This title already exists, are you sure you want to add it?</h3>");

               // Give the user a chance to fix the title or cancel the add
               modify_record($alias, 'Create Duplicate', $uid, $record, $srow, ADD_FORCE);
               $add_now = 0;                 // do not add the record yet
          }	
	}
     /*
     ** If the record isn't there, or if the user said to add anyway, then do it.
     */
     if( $add_now )
     {
	     db_add_record($alias, $record);         // call the database add record function
	     db_count_rows();                        // recount the rows in the database table
          $including = 'yes';                     // so display.php will not do things we already did
          include ('display.php');                // display the primary table
          $title = $record['title'];
          print("Added record $title to the database");
     }
}

include('footer.inc');
?>
