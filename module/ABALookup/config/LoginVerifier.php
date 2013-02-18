<?php
$display_name = $_POST['display_name'];
$password = $_POST['password'];

$min_display_name_length = 3;
$max_display_name_length = 12;
$min_password_length = 7;
$max_password_length = 30;

#Test for valid display_name
if (strlen($display_name) < $min_display_name_length || strlen($display_name) > $max_display_name_length)
	#blah
$user = $entityManager->find("User", $display_name)
if (!$user)
	#blah

#Test for valid password
if (strlen($password) < $min_password_length || strlen($password) > $max_password_length)
	#blah

#Test for valid login
if ($user->getPassword() != $password)
	#blah
else
	#login success

?>