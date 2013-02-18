<?php
require_once "bootstrap.php";

$min_display_name_length = 3;
$max_display_name_length = 12;
$min_password_length = 7;
$max_password_length = 30;
$invalid_characters = "#[^a-zA-Z0-9_-]#"

$therapist = $_POST['therapist'];
$display_name = $_POST['display_name'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

#Check that given display_name is correct length:
if (strlen($display_name) < $min_display_name_length || strlen($display_name) > $max_display_name_length)
	#blah
#Check that given display_name does not contain invalid characters:
if (preg_match($invalid_characters, $display_name) != 1)
	#blah
#Check that given display_name is not taken
if (!$entityManager->find("User", $display_name))
	#Blah
	
#Check that given email is valid format:
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	#blah
#Check that the given email is not taken:
if (!$entityManager->find("User", $email))
	#blah

#Check that given password is valid format:
if (strlen($password) < $min_password_length || strlen($password) > $max_password_length)
	#blah
#Check that password does not contain invalid characters.
if (preg_match($invalid_characters, $passwod) != 1)
	#blah
#Check that passwords match.
if ($password != $confirm_password)
	#blah

#Check that the given password 


#Success:
$user = new User($email, $display_name, $password, $therapist);
$entityManager->persist($user);
$entityManager->flush();