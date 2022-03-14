<?php
#Michael Sanchez
#insert transaction
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
#Else if the cookie is set, allow user to insert Transaction
#Check through data
$uid=$_COOKIE['user']; #grab uid from cookie
$con = mysqli_connect($host,$username, $password, $dbname); #establish connection
#---Grab Hidden data types--------- 
$name = $_POST['customer_name'];  

#$balance = $_POST['balance'];  
#================================================================================
#Calculate Balance 
#Set Default Balance to zero
$balance = 0;
#SQL query to search balance from money table at user id
$sql= " SELECT mid, type, amount FROM CPS3740_2021F.Money_sanchem1 m WHERE m.cid = '$uid'";
$result= mysqli_query($con, $sql);
#Check if there are records, if not balance set to zero
if (mysqli_num_rows($result) == 0)
		$balance = 0;
#Else we go through loop	
elseif($result) {
		while($row = mysqli_fetch_array($result)){
			$mid = $row["mid"];
			$amount = $row["amount"];
			$type = $row['type'];
			#Checks mid is not empty/NULL
			if ($mid <>"") {
				#Checks for type
				if($type == 'D'){
					$balance = $balance + $amount;} #if type = D then we add amount to balance
				else{
					$balance = $balance - $amount;}#else type = W, then we subtract from balance
								}}
				mysqli_free_result($result); #Free up resource
							}
#================================================================================
#Check Code down below using a sql query
$code= mysqli_real_escape_string($con, $_POST['code']);
#-------------------------------------------------------------
#Check the Following data is set first
#Checking Type
if(!isset($_POST['type']))
	die("Please select Deposit or Withdrawal.");
else
	$type= mysqli_real_escape_string($con, $_POST['type']);
#Checking Amount
if(!isset($_POST['amount']))  #is amount not set?
	die("Amount entered is invalid (ex. empty)");
elseif($_POST['amount'] <=0)    #is amount less or equal to zero?
	die("Amount entered is invalid number. (ex. Not a number, less or equal to zero.)");
elseif($_POST['amount'] > $balance AND $type == 'W')  #is amount a withdrawal greater than balance? 
	die("Insufficient Funds. Withdraw amount greater than balance.");
else	
	$amount= mysqli_real_escape_string($con, $_POST['amount']); #set amount

#Checking Source
if(!isset($_POST['source_id'])|| $_POST['source_id'] =='')
	die("Please select a source.");
else	
	$source_id= mysqli_real_escape_string($con, $_POST['source_id']);
#------------------------------------------------------------------
$note= mysqli_real_escape_string($con, $_POST['note']);
#===================================================================================================
#Check if code is a duplicate using SQL query
$sqlChkCode = "SELECT code FROM CPS3740_2021F.Money_sanchem1 WHERE  code = '$code'";
$result = mysqli_query($con, $sqlChkCode);
if(mysqli_num_rows($result) != 0)
	die("Code already exists in the database");
mysqli_free_result($result);
#===================================================================================================
#If all items pass the checks then we add record into database
$sqlAdd = "INSERT into CPS3740_2021F.Money_sanchem1(cid,code,type,amount,sid,note,mydatetime)VALUES('$uid','$code','$type','$amount','$source_id','$note',now() )";
#Insert into database, report if insert was succesful or failed.

if(mysqli_query($con,$sqlAdd)){
	echo "Success: New Record Added.";
}
else{
	echo "ERROR: Could not able to execute $sqlAdd. " . mysqli_error($con);
}

echo "</HTML>\n";
echo "</body>";
mysqli_close($con);	
?>