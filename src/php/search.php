<?php

include ('session.php');      // include the session handling code
include ('record.php');       // include the record handling code

/*
** This displays the beginning of the table that will contain
** the search information.
*/
function div_start_search()
{
     
     print("<div class=\"search\">
                <h1>Set Search Filter</h1>");
}

/*
** This prints an input button in the form
*/
function div_form_button_search($name,$button_label, $filter_prefix="Filter by")
{
    print("<button type=\"submit\" name=\"$name\" value=\"$button_label\" class=\"btn btn-default searchtype\">$filter_prefix $button_label</button>\r\n");
}

/*
** This prints a field in the form
*/
function div_form_field_search($field,$default,$size,$popdown,$label,$help)
{
    print("<div class=\"searchtype\">\r\n");
        print("<div class=\"form-group\">\r\n");
            add_field($field,$default,$size,$popdown,$help);
        print("</div>\r\n");
        div_form_button_search("stype",$label);
    print("</div>");
}

/*
** This prints the closing part of the table
*/
function div_end_search()
{
     print("</div>");
}

/*
** This is the search form that allows the user to filter their view
** based on keywords or an MPAA rating. The results from this form
** are sent directly back to the display.php page for processing.
*/
function display_form()
{
     $keyword_help = "Enter one or more keywords that you"
                   . " are looking for. If any of these"
                   . " words appear in the title, it will"
                   . " be displayed.";

	$ratings_help = "Select an MPAA rating from the popdown"
                   . " list. All titles that have the same"
                   . " rating will be displayed.";

	$genre_help = "Select a Genre from the popdown"
                   . " list. All titles that have the same"
                   . " genre will be displayed.";

	$form_help    = "<p>Press <em>Reset</em> to remove the search filter.</p>";

	div_start_search();
	print("<form method=\"POST\"action=\"display.php?setfilter=1\">\r\n");
    div_form_field_search('keywords','',255,NULL,"Keyword",$keyword_help);
    div_form_field_search('rating','G',7,get_ratings(),"Rating",$ratings_help);
    div_form_field_search('genre','Action',15,get_genres(),"Genre",$genre_help);
    print($form_help);
    div_form_button_search("stype","Reset", "");
    print("</form>\r\n");
	div_end_search();
}

include('header.inc');        // display the standard page header

display_form();               // display the search form

include('footer.inc');        // display the standard page footer

?>
