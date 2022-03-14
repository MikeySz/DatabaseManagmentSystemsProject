<?php
#Logout php file
#Michael Sanchez
#CSS Stylesheet refrence
echo"<link rel='stylesheet' href='Mstyle.css'>";
#if the cookie is set, then 'user' cookie is set to expire in 1 sec.
if(isset($_COOKIE['user'])){
	setcookie("user", ' ', 1);
	echo "You have been successfully logged out. <br>";
}
else
#Else tell the user, they are not logged in
echo "You are not logged in.<br>";

#Return to Homepage  
echo "<a href='index.html' target=_self>Return to Homepage</a>";

?>