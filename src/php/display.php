<?php

include_once ('functions.php');

/*
** Display the beginning of the table for the primary display page
*/
function table_start()
{
	$tbl_start = "<div class=\"movietable\"><table>"
		. "  <tr>"
		. "    <th colspan=\"2\">&nbsp;</th>";
		
	if (show_col('title')) $tbl_start .= "    <th>Title</th>";
	if (show_col('rating')) $tbl_start .= "    <th>Rating</th>";
	if (show_col('genre')) $tbl_start .= "    <th>Genre</th>";
	if (show_col('format')) $tbl_start .= "    <th>Format</th>";
	if (show_col('loanee')) $tbl_start .= "    <th>Loaned</th>";
	
	$tbl_start .= "  </tr>";
	
	print($tbl_start);
}

/*
** Read and display the rows from the database in the table
*/
function db_read_rows($start_row, $how_many)
{
    global $columns;

     // get the standard query, then add a LIMIT to it so we can get the page we want
	$result = mysql_query(db_get_query()." LIMIT $start_row,$how_many");

    $edit_form = EDIT_FORM;       // id that tells update.php to put up an edit form
    $delete_form = DELETE_FORM;   // id that tells delete.php to put up a delete form
	$counter = 0;

     // for each row in the table
	while($row = mysql_fetch_row($result)){
          $imdbid = trim(ltrim($row[5]));    // clean up the imdb ID if it's there so we can use it
		print("  <tr>\r\n");
          // the next two items are the edit and delete button's in the table
          // notice that we encode the UID of this record as a GET parameter, so the corresponding
          // form knows which record we are manipulating
        if (is_guest()){
            print("    <td>&nbsp;&nbsp;</td>\r\n");
            print("    <td>&nbsp;&nbsp;</td>\r\n");
        } else {
            print("    <td><a href=update.php?type=$edit_form&uid=$row[6]&start_row=$start_row><i class=\"fa fa-pencil ipencil\"></i></a></td>\r\n");
            print("    <td><a href=delete.php?type=$delete_form&uid=$row[6]&start_row=$start_row><i class=\"fa fa-times idelete\"></i></a></td>\r\n");
        }
          // If there is an IMDB ID for this title, then hyperlink the title text
          if ($imdbid)
          {
               print("    <td><a href=\"http://www.imdb.com/title/$imdbid\" target=\"_blank\">$row[0]</a></td>\r\n");
          }
          else
          {
		     print("    <td>$row[0]</td>\r\n");
		}
          // display the remaining items in the table.
		if (show_col('rating')) print("    <td>$row[1]</td>\r\n");
		if (show_col('genre')) print("    <td>$row[2]</td>\r\n");
		if (show_col('format')) print("    <td>$row[3]</td>\r\n");
		if (show_col('loanee')) print("    <td>$row[4]&nbsp;</td>\r\n");
		print("  </tr>\r\n");
		$counter += 1;
	}
}

/*
** If $including is defined, it means that either Add, Delete or Update is including
** this script to get a redisplay of the table. We don't want to include the
** common code or standard header again.
*/
if ($including == NULL)
{
     include_once ('session.php');
     include_once ('sqlfuncs.php');
     include ('header.inc');
}

table_start();                          // generate the start table HTML

$start_row = $_GET['start_row'];        // get the starting row
$set_filter = $_GET['setfilter'];       // get the filter ID if any
$how_many = ROWS_TO_DISPLAY;            // how many rows per page

/*
** if set_filter is 1, then the user selected to set a search filter
** and has chosen one of the following:
**
**   filter on keywords
**   filter on rating
**   reset the filter
**
** call the db_set_filter() API and it will figure out what to do
** and construct a new query to implement it.
*/
if ($set_filter == 1){
	$search = $_POST['stype'];
	$keywords = $_POST['keywords'];
	$rating = $_POST['rating'];
    $genre = $_POST['genre'];
	db_set_filter($search, $keywords, $rating, $genre);
	db_count_rows();
	$start_row = 0;
}

$total_rows = $_SESSION['total_rows'];       // how many rows?     

if (!IsSet($start_row)) $start_row = 0;      // assume starting row is 0

// connect to the database, populate the table rows, and close the connection
$link = db_connect();
db_read_rows($start_row, $how_many);
mysql_close($link);

// now draw the navigation links in the bottom of the table
$next_set = $start_row + $how_many;
$prev_set = $start_row - $how_many;
print("<tr bgcolor=\"#e5e5e5\" height=\"35\">");
print("<td colspan=7 align=\"center\">");

// the Add Movie link
if (!is_guest()){
    $add_form = ADD_FORM;
    print("|&nbsp;&nbsp;<a href=\"add.php?type=$add_form&uid=0&start_row=$start_row\">Add Movie</a>&nbsp;&nbsp;");
}
print("|&nbsp;&nbsp;<a href=\"display.php?start_row=0\">First Page</a>&nbsp;&nbsp;|&nbsp;&nbsp;");

// the Next link
if ($next_set >= $total_rows){
	print("Next $how_many");
}
else {
	print ("<a href=\"display.php?start_row=$next_set\">Next $how_many</a>");
}

print("&nbsp;&nbsp;|&nbsp;&nbsp;");

// the Prev link
if ($prev_set < 0 and ($prev_set + $how_many) == 0){
	print("Prev $how_many");
}
else {
	if ($prev_set < 0) $prev_set = 0;
	print("<a href=\"display.php?start_row=$prev_set\">Prev $how_many</a>");
}

print("&nbsp;&nbsp;|&nbsp;&nbsp;");

// the Last Page link
$last_row = $total_rows - $how_many ;	// used to be -1 from this???
if ($last_row < 0){
	print("Last Page");
}
else {
	print ("<a href=\"display.php?start_row=$last_row\">Last Page</a>");
}

print("&nbsp;&nbsp;|");
print("</td>\r\n</tr>\r\n");
print("</table>\r\n</div>\r\n");

// This is a debug statement, but I like having it, cause I can always
// tell how many records are in the database.
print ("Debug: Row count is $total_rows\r\n<br>");

//print ("Debug: " . db_get_query());

/*
** Once again, don't do things that add, delete or update will do for us
*/
if ($including == NULL)
{
     include ('footer.inc');
}
?>
