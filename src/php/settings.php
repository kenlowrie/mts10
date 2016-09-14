<?php

include_once ('functions.php');

function add_checkbox($column,$value,$checked,$help=NULL)
{

    $checkedstr = $checked ? "checked" : "";
    // get a friendly upper cased first letter version of the variable for display
    $col_name = ucfirst($column) . ':';
	//print("<label for=\"$column\">$col_name</label>\r\n");

    print("<div class=\"checkbox\">");
    print("    <label for=\"$column\"><input type=\"checkbox\" name=$column value=\"$value\" $checkedstr>$column</label>\r\n");

    if ($help != NULL)   // if not NULL, then user is specifying the string
    {
         print("<span class=\"helptext\">$help</span>\r\n");
    }
    print("</div>\r\n");
}

/*
** This displays the beginning of the table that will contain
** the search information.
*/
function div_start_settings()
{
     print("<div class=\"settings\">");
}

/*
** This prints an input button in the form
*/
function div_form_button_settings($name,$button_label)
{
    print("<button type=\"submit\" name=\"$name\" value=\"$button_label\" class=\"btn btn-default\">$button_label</button>\r\n");
}

/*
** This prints a field in the form
*/
function div_form_field_settings($field,$value,$checked,$help)
{
    print("<div class=\"settingsform\">\r\n");
        print("<div class=\"form-group\">\r\n");
            add_checkbox($field,$value,$checked,$help);
        print("</div>\r\n");
    print("</div>");
}

/*
** This prints the closing part of the table
*/
function div_end_settings()
{
     print("</div>");
}

// Update the settings if this is a post of the form...

function update_settings()
{
    $save  = $_GET['save'];       // my edit type as a GET parameter

    if( IsSet($save) ){
        // We are posting the form, so read the values and store them in the session object
        $rating = IsSet($_POST['Rating']) ? True : False;
        $genre = IsSet($_POST['Genre']) ? True : False;
        $format = IsSet($_POST['Format']) ? True : False;
        $loanee = IsSet($_POST['Loanee']) ? True : False;
    
        $cd = get_display_columns_object();
    
        $cd->setShowRating($rating);
        $cd->setShowGenre($genre);
        $cd->setShowFormat($format);
        $cd->setShowLoanee($loanee);
    
        set_display_columns_object($cd);
        print("<div class=\"message\"><p>Settings have been updated...</p></div>");
    }
}

/*
** This is the search form that allows the user to filter their view
** based on keywords or an MPAA rating. The results from this form
** are sent directly back to the display.php page for processing.
*/
function display_settings()
{
    update_settings();
    
    $show_genre = "- Show genre column";
    $show_rating = "- Show rating column";
	$show_loanee = "- Show loanee column";
	$show_format = "- Show format column";

    print("<h1>Settings</h1>");
    print("<p>Choose which columns will be displayed in the movie table. In the add or update movie forms, all fields will be displayed.</p>\r\n");
	print("<form method=\"POST\"action=\"settings.php?save=1\">\r\n");
    $cd = get_display_columns_object();
	div_form_field_settings("Rating","1",$cd->showRating(),$show_rating);
	div_form_field_settings("Genre","1",$cd->showGenre(),$show_genre);
	div_form_field_settings("Format","1",$cd->showFormat(),$show_format);
	div_form_field_settings("Loanee","1",$cd->showLoanee(),$show_loanee);
    div_form_button_settings("","Update Settings", "");
    print("</form>\r\n");
}

include('header.inc');        // display the standard page header

div_start_settings();

display_settings();               // display the search form

div_end_settings();

include('footer.inc');        // display the standard page footer

?>
