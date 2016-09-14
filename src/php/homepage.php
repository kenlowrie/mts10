<?php
include ('session.php');
include ('sqlfuncs.php');
/*
** This script is responsible for drawing the content when the "home" link is pressed.
** Display the default content, which is housed in the mainbody.inc HTML file.
**
*/

include('header.inc');		// This is the standard header

include('mainbody.inc');	// Display the default content

include('footer.inc');			// Display the standard footer

?>
