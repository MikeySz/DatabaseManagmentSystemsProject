<?php
#Michael Sanchez
#Displays Staff Table
#--------------------------------------------------------------------------
#use dbconfig to connect to database
include "dbconfig.php";
$con = mysqli_connect($host,$username, $password, $dbname);
#----------------------------------------------------------------------------
#SQL query, we want to display all records and columns in the dreamhome.Staff table
$sql = " SELECT * FROM dreamhome.Staff "; 
$result = mysqli_query($con, $sql);
#-------------------------------------------------------------------------------
#As long as there is a result, then we run the code
if($result) {
	#note created the headings manually, addition of columns would require manual addition to code. 
#----------------------Headings---------------------------------------------------
	echo "<TABLE border = 1>";
	echo "<TR><TH>staffNo
				<TH>fName
				<TH>lName
				<TH>position
				<TH>sex
				<TH>DOB
				<TH>salary
				<TH>branchNo";
#---------------------------------------------------------------------------------
#Fetch the data 				
	while($row = mysqli_fetch_array($result)){
		$staffNo = $row['staffNo'];
		$fName = $row['fName'];
		$lName = $row['lName'];
		$position = $row["position"];
		$sex = $row["sex"];
		$DOB = $row["DOB"];
		$salary = $row["salary"];
		$branchNo = $row["branchNo"];
#--------------------------------------------------------------------------------
#Take data and put it into a HTML table	
#as long as the primary key is not empty/NULL then we display the row data	
		if ($staffNo <>""){  
			echo "<br><TR><TD>$staffNo<TD>$fName<TD>$lName<TD>$position";
			#Check if sex is 'M', if so it will be blue. else we assume 'F' and we will want the data to be red.
			if($sex == 'M')
				echo "<TD STYLE = 'color:blue'>$sex";
			else
				echo "<TD STYLE = 'color:red'>$sex";

			echo "<TD>$DOB<TD>$salary<TD>$branchNo \n";
		}
}
	echo "</TABLE>"; #close table
}

mysqli_free_result($result);
mysqli_close($con);
?>