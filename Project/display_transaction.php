<?php
#Display and Update Transaction
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
#Else if the cookie is set, allow user to View Transactions before update/delete
#get uid from cookie and create connection
$uid=$_COOKIE['user'];
$con = mysqli_connect($host,$username, $password, $dbname);
echo "You can only update the <b>Note</b> Column.<br>";

#---------------------------------------------------------------------------------------------------------------------
#display Customer's Transactions 
				#SQL query to retrieve customer's transaction 
						$sql= " SELECT mid, code, type, amount, sid,s.name as source, mydatetime, note FROM CPS3740_2021F.Money_sanchem1 m, CPS3740.Sources s WHERE m.sid = s.id AND m.cid = '$uid'";

					$result= mysqli_query($con, $sql);
					#Check if there are records that can be updated, else end the operation
					if (mysqli_num_rows($result) == 0)
						die("Can not update, No records found.");

					#Balance, used to keep track of the total for the transactions currently displayed
					$balance = 0;
					#Counter
					$i= 0;

					echo "<form action='update_transaction.php' method='post'>";
						#Creates Transaction Table
						if($result) {
							echo "<TABLE border = 1> ";
							echo "<TR><TH>ID<TH>Code<TH>Amount<TH>Type<TH>Source<TH>Date Time<TH>Note";
							echo"<TH>Delete";
							while($row = mysqli_fetch_array($result)){
								$id = $row['mid'];
								$code = $row['code'];
								$amount = $row["amount"];
								$type = $row['type'];
								$source = $row['source'];
								$sid = $row['sid'];
								$datetime = $row['mydatetime'];
								$note = $row["note"];

								#Checks mid is not empty/NULL
								if ($id <>"") {
									echo "<br><TR><TH>". $id . "<TD>$code";
								
								if($type == 'D'){
									echo "<TD STYLE = 'color:blue'>$amount<TD>Deposit ";
									$balance = $balance + $amount;}
								else{
									echo"<TD STYLE = 'color:red'>$amount<TD>Withdraw ";
									$balance = $balance - $amount;}

								echo"<TD>$source<TD>$datetime";
								echo"<TD bgcolor='yellow'><input type='text' value='$note' name='note[$i]' style='background-color:yellow;'></TD> ";
								echo "<TD><input type='checkbox' name='cdelete$i'>
										<input type='hidden' name='cid[$i]' value='$uid'>
										<input type='hidden' name='sid[$i]' value='$sid'>
										<input type='hidden' name='mid[$i]' value='$id'>
										<input type='hidden' name='code[$i]' value='$code'>	";
								echo"\n";
								#increment counter
								if($i < mysqli_num_rows($result))
								$i=$i+1;
								}
																}
								echo "</TABLE>";  #End of Transaction Table
								#Show Balance, display in blue if it is greater or equal to zero else show red for negative.		
								if ($balance >= 0 )
								echo "Total Balance: <font STYLE = 'color:blue'>". $balance ."</font>\n";
								else
								echo "Total Balance: <font STYLE = 'color:red'>". abs($balance) ."</font>\n";

								echo"<br><input type='submit' value='Update Transaction'> </form>";

								mysqli_free_result($result);
									}
							
echo "</body>";
echo "</HTML>";
mysqli_close($con);

?>