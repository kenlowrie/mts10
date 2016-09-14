<?php

/*
** The common functions for handling the record forms
** are in this file. These functions are shared by the
** add.php, update.php and delete.php scripts.
*/

/*
** Returns the standard list of MPAA ratings
*/
function get_ratings()
{
	return 'G|PG|PG13|NC17|NR|R|TV14|E|T|M|KA';
}

/*
** Returns the standard list of Genre's
*/
function get_genres()
{
	return 'Action|Animated|Children|Comedy|Drama|GAME|Horror|RomCom|Thriller';
}

/*
** Return the help string for the specified field.
*/
function get_help($key)
{
     switch($key)
     {
     case 'title':
          $str = "Enter the title for the movie.";
          break;
     case 'format':
          $str = "Select the format. WS stands for WideScreen and FS stands for FullScreen.";
          break;
     case 'genre':
          $str = "Select the genre for the movie.";
          break;
     case 'rating':
          $str = "Select the MPAA rating.";
          break;
     case 'loanee':
          $str = "Enter the name of the person that"
               . " currently has the movie out on loan,"
               . " or clear the field if the movie has"
               . " been returned.";
          break;
     case 'imdbid':
          $str = "Enter the <a href=\"http://www.imdb.com\" target=\"_new\">"
               . " IMDB</a> movie ID for this title. This will cause the"
               . " title to be a hyperlink to the IMDB site.";
          break;
     default:
          $str = NULL;
     }
     return $str;
}

/*
** Add a field to the form. Here is how everything is interpreted:
**
** $column is the name of the column (field). It will also be the _POST variable name.
** $value is the default or current value to display.
** $maxlen is the maximum number of characters that can be typed into a text field
** $popdown is NULL if this is a text field, otherwise it is a | separated list of values for a popdown menu.
** if $help is 1 then perform a lookup of the help string.
** if $help is NULL if no help should be displayed
** if $help is the help string for this field.
**
** this function prints one entire table row
*/

function add_field($column,$value,$maxlen,$popdown,$help=NULL)
{
     // get a friendly upper cased first letter version of the variable for display
     $col_name = ucfirst($column) . ':';
	print("<label for=\"$column\">$col_name</label>\r\n");

     // if $popdown is NULL, this is a plain text field
	if ($popdown == NULL){
	    print("<input class=\"form-control\" type=\"text\" size=\"30\" maxlength=\"$maxlen\" name=$column value=\"$value\" placeholder=\"Enter $column\">\r\n");
	} else {
        // this is a popdown menu, construct the select statement for it by
        // breaking down the | separated list of values. take note of each
        // value to see if it matches $value, and then have it selected by
        // default.     
		print("<select class=\"form-control\" name=$column>");
    	     $option = strtok($popdown,'|');
		while($option){
			if ($option == $value)
				print("<option selected=\"selected\">$option</option>");
			else
				print("<option>$option</option>");
			$option = strtok("|");
		}
		print("</select>\r\n");
	}
     // if $help is 1 then go do a lookup for the help string
     if($help == 1) 
     {
          $helpstr = get_help($column);
          if ($helpstr) print("<p>$helpstr</p>\r\n");
     }
     elseif ($help != NULL)   // if not NULL, then user is specifying the string
     {
          print("<p>$help</p>\r\n");
     }
}

/*
** This displays the beginning of the table that will contain
** the search information.
*/
function div_start()
{
     print("<div class=\"addormodify\">\r\n");
}

/*
** This prints an input button in the form
*/
function div_form_button($name,$button_label)
{
    print("<button type=\"submit\" name=\"$name\" value=\"$button_label\" class=\"btn btn-default update\">$button_label</button>\r\n");
}

/*
** This prints a field in the form
*/
function div_form_field($field,$default,$size,$popdown,$help)
{
    print("<div class=\"update\">\r\n");
        print("<div class=\"form-group\">\r\n");
            add_field($field,$default,$size,$popdown,$help);
        print("</div>\r\n");
    print("</div>");
}

/*
** This prints the closing part of the table
*/
function div_end()
{
     print("</div>");
}

/*
** this is the generic function that displays the edit, add or delete form. the parameters are:
**
** $alias - the name of the script that will process the form
** $modtype - the string describing what we are doing: editing, deleting, adding ...
** $uid - the unique ID of the record being edited or deleted
** $record - a standard record array containing all the fields for the record
** $srow - the start_row value to pass to the display script
** $commit_id - the edit type that will be passed to the script processor so it knows what to do
** $showhelp - whether or not the caller wants the help strings displayed on the form
*/
function modify_record($alias, $modtype, $uid, $record, $srow, $commit_id, $showhelp=1)
{
	$title  = $record['title'];
	$format = $record['format'];
	$genre  = $record['genre'];
	$rating = $record['rating'];
	$loanee = $record['loanee'];
	$imdbid = $record['imdbid'];

     // construct the form, and then the outer table that will hold the field definitions
	div_start();
	print("<h2>$modtype Record: $uid</h2>");

	print("<form method=\"POST\" action=\"$alias?type=$commit_id&uid=$uid&start_row=$srow\">\r\n");

    // now add one row per field value
    div_form_field('title',$title,255,NULL,$showhelp);
    div_form_field('format',$format,7,'FS|WS|N64|PS|PS2|XBOX|XBOX360',$showhelp);
    div_form_field('genre',$genre,31,get_genres(),$showhelp);
    div_form_field('rating',$rating,7,get_ratings(),$showhelp);
    div_form_field('loanee',$loanee,255,NULL,$showhelp);
    div_form_field('imdbid',$imdbid,31,NULL,$showhelp);

    // now do another table to hold the buttons for the action or cancel function of the form
    print("<div class=\"inline\">\r\n");
        div_form_button($modtype,$modtype);
        print("</form>\r\n");

        print("<form method=\"POST\" action=\"display.php?start_row=$srow\">");
        div_form_button("Cancel", "Cancel");
        print("</form>");
    print("</div>\r\n");
	
	div_end();
}

