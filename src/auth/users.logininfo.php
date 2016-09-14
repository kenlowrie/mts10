<?php
/*
**	User Types:
**		0 - admin user - can do DB operations
**		1 - normal user - can add movies, but no DB operations
**		2 - guest account - can only browse the collection
*/
$users->addUser('ADMIN_USER',0,'ADMIN_PASSWORD');
$users->addUser('NORMAL_USER',1,'USER_PASSWORD');
$users->addUser('guest',2,'');
?>
