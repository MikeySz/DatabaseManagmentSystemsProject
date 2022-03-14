<?php
#Michael Sanchez
#Add Transaction Page
include ("dbconfig.php");
echo "<HTML>\n";
#CSS Stylesheet refrence
echo"<link rel='stylesheet' href='Mstyle.css'>";
echo "<body>";
#---------------------------------------------------------------------------------------------------------------------------
#if the cookie is not set, then tell the user to login.
if(!isset($_COOKIE['user']))
	die("Please login first.<br>
		<a href='index.html' target=_self>Return to Homepage</a>");

#Else if the cookie is set, allow user to add Transaction
$uid=$_COOKIE['user'];
$con = mysqli_connect($host,$username, $password, $dbname);
$name = $_POST['customer_name'];
$balance = $_POST['customer_balance'];
#---------------------------------------------------------------------------------------------------------------------
#allows for Logout 
echo "<a href='logout.php' target=_self>User Logout</a><br>";
#---------------------------------------------------------------------------------------------
#SQL

#-----------------------------------------------------------------
#Transaction Page
echo"<br><font size = '4'><b>Add Transaction</b></font><br>
		<b> $name </b> current balance is <b>$balance</b> .<br>"; #Display name and balance
#---------------------------------------------------------------------------------------
#Create form; will hold the items to be used in insert_transaction.php
echo"<form name='input' action='insert_transaction.php' method='post' required='required'>  "  ;
echo"<input type='hidden' name='customer_name' value='$name'>";
echo"Transaction Code:
		<input type='text' name='code' required = 'required'>
		<br>
		<input type='radio' name='type' value='D'>
		Deposit
		<input type='radio' name='type' value='W' 3=''>
		Withdraw
		<br>
		Amount:
		<input type='text' name='amount' required = 'required'>
		<input type='hidden' name='balance' value='$balance'><br>
		Select a Source:
		";

	$sqlSources = " SELECT id, name FROM CPS3740.Sources";
	$sResult = mysqli_query($con, $sqlSources);
	if($sResult) {

							
							echo "<select name = 'source_id'>";
							echo "<option value = ''></option>";
							while($row = mysqli_fetch_array($sResult)){
								$sid = $row['id']; 
								$sName = $row['name'];

								#Checks name is not empty/NULL
								if ($sid <>"") {
									echo "<option value = '$sid'> $sName </option>";
								}
							}
							echo"</select><br>";
							}
echo "Note: <input type='text' name='note'><br>
	<input type = 'submit' value = 'Submit'></form>";

echo"</body>";
echo "</HTML>";	
mysqli_free_result($sResult);
mysqli_close($con);	
?>