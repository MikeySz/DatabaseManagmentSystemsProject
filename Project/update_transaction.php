<?php
#Michael Sanchez
#Update/Delete transaction
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
#Else if the cookie is set, allow user to update/delete Transaction
$uid=$_COOKIE['user']; #grab uid from cookie
$con = mysqli_connect($host,$username, $password, $dbname); #establish connection
#get element arrays
$cid = $_POST['cid'];
$sid = $_POST['sid'];
$mid = $_POST['mid'];
$code = $_POST['code'];
$notes = $_POST['note'];
#Total length  
$length = count($cid);
#Counters
$i = 0; #general
$rd = 0; #Records Deleted
$ru = 0; #Records Updated
#============================================================================== 
#Using While loop to check records
#Run loop as long as counter doesn't match total length of items in arrays
while($i < $length){
#------------------------------------------------------------------------------	
#Delete Checkbox is unmarked:False	
if(!isset($_POST['cdelete'.$i])){
	#echo"$i False <br>";
	#Temp Files
	$codeT= mysqli_real_escape_string($con, $code[$i]);
	$midT= mysqli_real_escape_string($con, $mid[$i]);
	$noteT= mysqli_real_escape_string($con, $notes[$i]);
	#-----------------------------------
	#Check if note is different
	$sqlChk = "SELECT note from CPS3740_2021F.Money_sanchem1 WHERE cid = '$uid' AND mid = '$midT' AND note = '$noteT' ";
	#Show SQL debugging: Comparison query
	#echo"$sqlChk <br>";
	$resultChk = mysqli_query($con,$sqlChk);
	#check if there are 0 rows, if so we update. else we do nothing.
	if(mysqli_num_rows($resultChk) == 0){
		$sqlUpdate ="UPDATE CPS3740_2021F.Money_sanchem1 set note = '$noteT' where cid = '$uid' AND mid = '$midT' ";
		#Show SQL debugging: Update Query
		#echo "$sqlUpdate<br>";
		#Execute Query
		if(mysqli_query($con,$sqlUpdate)){
			#indicate that code has been Updated
			echo("The note for code $codeT has been Updated in the database.<br> ");
			}
		else{
			#If error occurs, display error message
		echo "ERROR: Could not able to execute $sqlUpdate. " . mysqli_error($con);
		}
		#increment record update counter
		$ru = $ru+1;
	}

}
#-------------------------------------------------------------------------------
#Delete Checkbox is marked: True
elseif(isset($_POST['cdelete'.$i])){
	#echo"$i True <br>";
	#Temp Files
	$codeT= mysqli_real_escape_string($con, $code[$i]);
	$sidT= mysqli_real_escape_string($con, $sid[$i]);
	$midT= mysqli_real_escape_string($con, $mid[$i]);
	#--------------------------------------
	$sqlDel= "DELETE FROM CPS3740_2021F.Money_sanchem1 WHERE cid='$uid' AND code='$codeT' AND sid='$sidT'   AND mid='$midT'";
	#Show SQL Debugging:Delete Query
	#echo("$sqlDel <br>");
	#-------------------------------------
	#Execute Query
	if(mysqli_query($con,$sqlDel)){
		#indicate that code has been deleted
		echo("The code $codeT has been deleted from the database.<br> ");
	}
	else{
		#If error occurs, display error message
		echo "ERROR: Could not able to execute $sqlDel. " . mysqli_error($con);
	}
	#Increase record deleted counter
	$rd = $rd+1;
}
#--------------------------------------------------------------------------
#increment $i at the end of loop
$i=$i+1;
}
#===================================================================================

#echo"$length"; #Testing only, array lengths should be equal between $cid, $sid, $mid, $code, $notes, and $_post[cdelete]
#=================================================================================
echo"<br>Transactions Deleted: $rd | Transactions Updated $ru .";

echo "</HTML>\n";
echo "</body>";
mysqli_close($con);	
?>