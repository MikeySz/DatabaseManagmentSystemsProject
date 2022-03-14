<?php
#Michael Sanchez
#login.php


#Use the dbconfig.php file to set a connection to the database, run the query, and store it into $result
define("IN_CODE", 1);
include ("dbconfig.php");
echo "<HTML>\n";
#CSS Stylesheet refrence
echo"<link rel='stylesheet' href='Mstyle.css'>";
echo "<body>";

$con = mysqli_connect($host,$username, $password, $dbname);
#---------------------------------------------------------------
#obtains the user input from the post form within the index.html
$busername= mysqli_real_escape_string($con, $_POST['username']);
$bpassword= mysqli_real_escape_string($con, $_POST['password']);

#echo "user: $busername, password: $bpassword\n";  # Debugging only, Shows the username and password entered

#----------------------------------------------------------------------------------------------------------------------------------
$sql = "SELECT id, login, password FROM CPS3740.Customers WHERE login='$busername' ";
$result = mysqli_query($con, $sql);

#echo "<br>SQL: $sql\n"; #Debugging only, shows the sql statement
#--------------------------------------------------------------------------------------------------------------------------------
if($result) {
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_array($result)){
			$clogin = $row["login"];
			$cpassword = $row['password'];
			$cid =$row['id'];
			#Checks if entered password exists within the query. If so tell the user login is successful and set a cookie
			if ($cpassword == $bpassword){  
				#echo "<br>Login Successful";
				setcookie("user", $cid, time()+60*60);
#========================================================================================================================================
#User HomePage
				#allows for Logout 
				echo "<a href='logout.php' target=_self>User Logout</a><br>";
				#Var to hold IP Address string
				$ip = " ".$_SERVER['REMOTE_ADDR'];
				#Sql Statment & query to get the (logged in)User's information; [0]=name,[1]=Age,[2]=Address,[3]=img
				$sql2 = "SELECT name, TIMESTAMPDIFF(year,dob,curdate()) as Age, concat( street, ', ', city, ', ', state,', ',zipcode) 'Address',img FROM CPS3740.Customers WHERE login='$busername' AND password='$bpassword' ";
				$result2 = mysqli_query($con, $sql2);
				#retrieves the row, and turns into usable vars
				$row2 = mysqli_fetch_row($result2);
						$name = $row2[0];
						$age = $row2[1];
						$address =$row2[2];
						$img = $row2[3];

				#Return IP Address
				echo "Your IP:".$ip.'<br>';
				#Return the the browser being used
				echo "Your Browser and OS: ".$_SERVER['HTTP_USER_AGENT'].'<br>';
				#Check if User is(or is NOT) from Kean
				if(strpos( $ip," 10.") !== false || strpos( $ip," 131.125.") !== false )
					echo("You are from Kean University. <br>");
				else echo "You are NOT from Kean University. <br>";
				#Customer greeting, uses name retrived throught result2
				echo "Welcome Customer: <b>".$name."</b> <br>";
				#display Customer age
				echo "age: ".$age." <br>";
				#display Customer address
				echo "Address: ".$address." <br>";
				#display Customer image
				echo"<img src='data:image/jpeg;base64,".base64_encode($img)."'/>";
#-------------------------------------------------------------------------------------------------------------------------------------------------------------
                #display Customer's Transactions
				#SQL query to retrieve customer's transaction history from the database
					$sqlMoney = " SELECT mid, code, type, amount, s.name as source, mydatetime, note FROM CPS3740_2021F.Money_sanchem1 m, CPS3740.Sources s WHERE m.sid = s.id AND m.cid = '$cid' ";
					$resultM = mysqli_query($con, $sqlMoney);

					#Shows how many transactions there are for the current logged in user
					echo "<hr> There are <b>". mysqli_num_rows($resultM) ."</b> Transactions for customer <b>".$name."</b>\n";
					$balance = 0;


						#Creates Transaction Table
						if($resultM) {
							echo "<TABLE border = 1> ";
							echo "<TR><TD>ID<TD>Code<TD>Type<TD>Amount<TD>Source<TD>Date Time<TD>Note";
							while($row3 = mysqli_fetch_array($resultM)){
								$mid = $row3['mid'];
								$mcode = $row3['code'];
								$mType = $row3['type'];
								$mAmount = $row3["amount"];
								$mSource = $row3['source'];
								$mDatetime = $row3['mydatetime'];
								$mNote = $row3["note"];

								#Checks mid is not empty/NULL
								if ($mid <>"") {
									echo "<br><TR><TH>". $mid . "<TD>$mcode";
								
								if($mType == 'D'){
									echo "<TD>Deposit <TD STYLE = 'color:blue'>$mAmount";
									$balance = $balance + $mAmount;}
								else{
									echo"<TD>Withdraw <TD STYLE = 'color:red'>$mAmount";
									$balance = $balance - $mAmount;}

								echo"<TD>$mSource<TD>$mDatetime";
								echo"<TD>$mNote \n";

								}
																}
								echo "</TABLE>";  #End of Transaction Table
									}
							#Show Balance, display in blue if it is greater or equal to zero else show red for negative.		
								if ($balance >= 0 )
								echo "Total Balance: <font STYLE = 'color:blue'>". $balance ."</font>\n";
								else
								echo "Total Balance: <font STYLE = 'color:red'>". abs($balance) ."</font>\n";
							#---------------------------------------------------------------------------------
							# Transaction Table
							echo"<table border = 0> <tbody><tr>"; 
							#Add Transactions, uses add_transaction.php
							echo"<td><form action='add_transaction.php' method='POST' > <input type='hidden' name='customer_name' value ='$name'><input type='hidden' name='customer_balance' value ='$balance'> <input type='submit' value ='Add Transaction'></form></td>";
							#Hyperlinks to display/update transaction and display stores
							echo"<td><a href='display_transaction.php'>Display and Update Transactions</a> &nbsp <a href='display_stores.php'>Display Stores</a> </td></tr>";
							#Search through transactions using keywords in notes
							echo "<tr> <td colspan='2'> ";
							echo "<form action='search_transaction.php' method = 'get'> Keyword:";
							echo "<input type='text' name='keyword' required='required'> &nbsp<input type='hidden' name='customer_name' value ='$name'>";
							echo "<input type='submit' value='Search Transaction'></form></td></tr>";
							echo "</tbody></table>";  #Close Transaction Table


#============================================================================================================================================
#Free up resources
#mysqli_free_result($result);
mysqli_free_result($result2);
mysqli_free_result($resultM);
			}
			else{
				echo"<a href='index.html' target=_self>Return to Homepage</a>";#return to homepage link
				echo "<br> User: <b>$busername</b> exists but Password is invalid"; #runs if the password is not found in the database, but username is valid
				}
			}
	}
	else{
		echo"<a href='index.html' target=_self>Return to Homepage</a>";#return to homepage link
		echo "<br> No such user: <b>$busername</b>  exists in the database";   #if $result has no rows, then no such user exists
		}
}
	else {
		echo"<a href='index.html' target=_self>Return to Homepage</a>";#return to homepage link
		echo "<br> something went wrong!";    #if all else fails, then tell user something went wrong
}
echo"</body>";
echo "</HTML>";
#mysqli_close($con);
?>