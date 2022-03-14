<?php
#Michael Sanchez
#Search Transaction Page
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

#Else if the cookie is set, allow user to search transactions
$uid=$_COOKIE['user'];
$con = mysqli_connect($host,$username, $password, $dbname);
$keyword= mysqli_real_escape_string($con, $_GET['keyword']);
$name = $_GET['customer_name'];
#---------------------------------------------------------------------------------------------------------------------
#display Customer's Transactions 
				#SQL query to retrieve customer's transaction based on keyword
					#If the keyword is * then we return all records, else we use the LIKE operator at the current user's id
					if ($keyword == "*")
						$sqlSearchM = " SELECT mid, code, type, amount, s.name as source, mydatetime, note FROM CPS3740_2021F.Money_sanchem1 m, CPS3740.Sources s WHERE m.sid = s.id AND m.cid = '$uid'";
					else
						$sqlSearchM = " SELECT mid, code, type, amount, s.name as source, mydatetime, note FROM CPS3740_2021F.Money_sanchem1 m, CPS3740.Sources s WHERE m.sid = s.id AND m.cid = '$uid' AND note like '%$keyword%' ";

					$resultSearchM = mysqli_query($con, $sqlSearchM);
					#We check the number of rows before creating table, if rows equal zero we set $resultSearchM to false, thus table will not be created.
					if (mysqli_num_rows($resultSearchM) == 0)
						$resultSearchM = False;

					#Balance, used to keep track of the total for the transactions currently displayed
					$balance = 0;


						#Creates Transaction Table
						if($resultSearchM) {

							if ($keyword == "*")
								echo "The Transactions in customer <b>".$name."</b> records\n";
							else
								echo "The Transactions in customer <b>".$name."</b> records matching keyword <b>".$keyword."</b> are:\n";


							echo "<TABLE border = 1> ";
							echo "<TR><TH>ID<TH>Code<TH>Type<TH>Amount<TH>Date Time<TH>Note<TH>Source";
							while($row = mysqli_fetch_array($resultSearchM)){
								$mid = $row['mid'];
								$mcode = $row['code'];
								$mType = $row['type'];
								$mAmount = $row["amount"];
								$mSource = $row['source'];
								$mDatetime = $row['mydatetime'];
								$mNote = $row["note"];

								#Checks mid is not empty/NULL
								if ($mid <>"") {
									echo "<br><TR><TH>". $mid . "<TD>$mcode";
								
								if($mType == 'D'){
									echo "<TD>Deposit <TD STYLE = 'color:blue'>$mAmount";
									$balance = $balance + $mAmount;}
								else{
									echo"<TD>Withdraw <TD STYLE = 'color:red'>$mAmount";
									$balance = $balance - $mAmount;}

								echo"<TD>$mDatetime";
								echo"<TD>$mNote<TD>$mSource \n";

								}
																}
								echo "</TABLE>";  #End of Transaction Table
								#Show Balance, display in blue if it is greater or equal to zero else show red for negative.		
								if ($balance >= 0 )
								echo "Total Balance: <font STYLE = 'color:blue'>". $balance ."</font>\n";
								else
								echo "Total Balance: <font STYLE = 'color:red'>". abs($balance) ."</font>\n";

								mysqli_free_result($resultSearchM);
									}
							#This will display when no records are found. (Most likely after $resultSearchM is set to false)		
							else echo "No records found with the search keyword: ".$keyword;
							#---------------------------------------------------------------------------------
echo "</body>";
echo "</HTML>";
mysqli_close($con);

?>