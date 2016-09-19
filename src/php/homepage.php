<?php
include ('session.php');
include ('sqlfuncs.php');
/*
** This script is responsible for drawing the content when the "home" link is pressed.
** Display the default content, which is housed in the inc/_mainbody.html file.
**
*/

include('inc/_def_header.html');		// This is the standard header

include('inc/_mainbody.html');	// Display the default content

include('inc/_def_footer.html');			// Display the standard footer

?>
