<?php
#Display Stores
#Michael Sanchez
include ("dbconfig.php");
echo "<HTML>\n";
#CSS Stylesheet refrence
echo"<link rel='stylesheet' href='Mstyle.css'>";
echo "<body>";
#--------------------------------------------------------------------------------------------------------
#if the cookie is not set, then tell the user to login.
if(!isset($_COOKIE['user']))
	die("Please login first.<br>
		<a href='index.html' target=_self>Return to Homepage</a>");

#allows for Logout 
echo "<a href='logout.php' target=_self>User Logout</a><br>";
#create connection
$con = mysqli_connect($host,$username, $password, $dbname);


#SQL query that will retrieve the data we want to display on display stores page
$sql = " SELECT sid, Name,  address, city, State, Zipcode, concat(latitude, longitude) AS Location from CPS3740.Stores where concat(Name,Zipcode,State,City,address,latitude,longitude) is not Null;
 ";
$result = mysqli_query($con, $sql);

echo"<b>The following stores are in the database.</b>";
if($result) {
	echo "<TABLE border = 1> ";
	echo "<TR><TH>ID<TH>Name<TH>Address<TH>City<TH>State<TH>Zipcode<TH>Location(Latitude,Longitude)";
	while($row = mysqli_fetch_array($result)){
		$id = $row['sid'];
		$Name = $row['Name'];
		$address = $row['address'];
		$city = $row["city"];
		$state = $row["State"];
		$zipcode = $row["Zipcode"];
		$location = $row["Location"];

		
		if ($id <>"") 
			echo "<br><TR><TH>". $id . "<TD>$Name<TD>$address<TD>$city<TD>$state<TD>$zipcode<TD>$location \n";
}
	echo "</TABLE>";
}

echo "</body>";
echo "</HTML>";
mysqli_free_result($result);
mysqli_close($con);
?>