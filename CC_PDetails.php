<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
	<a href="CC_Summary.php"> Back</a> </BR>
<?php
	session_start();
	$serverName = "172.23.4.250"; //serverName\instanceName
	$connectionInfo = array( "Database"=>"FLP", "UID"=>"sa", "PWD"=>"p@ssw0rd", "CharacterSet" => "UTF-8");
	$conn = sqlsrv_connect( $serverName, $connectionInfo);
	$dist=$_SESSION["dist"];
	$pcc=0;
	$nmcc=0;
	$totalcc=0;
	echo "<h2> Personal CC </h2>";
	echo "<table border='0' width='500px'>";
	echo "<tr><td width='250px'>" . "FBO" . "</td><td width='50px'>" . "Order ID"  . "</td><td width='50px'>" ."Date". "</td><td>" . "cc" . "</td></tr>";
	if( $conn ) {
		$sql_callsp = "Exec dbo.getDownLineOrder '" . $dist ."'";
		$stmt3 = sqlsrv_query( $conn, $sql_callsp);
		if( $stmt3 === false )
		{
			echo "Error in executing statement 3.\n";
			die( print_r( sqlsrv_errors(), true));
		}
		while( $row = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC) ) 
		{
			if ($row['dist_id'] == $dist)
			{
			echo "<tr>";			
			echo "<td width='250px'>" . $row['name']. " (".$row['dist_id']. ") </td><td width='100px'>" . $row['ord_id']  . "</td><td width='100px'>" .$row['order_date']->format('Y-m-d'). "</td><td>" . $row['total_cc'] . "</td>";
			echo "</tr>";
			}
      		
		}
		echo "</table>";
		
	}else{
		 echo "Connection could not be established.<br />";
		 die( print_r( sqlsrv_errors(), true));
	}
	// Close the connection.
	sqlsrv_close($conn);
?>
</body>
</html>