<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php
	session_start();
	$serverName = "172.23.4.250"; //serverName\instanceName
	$connectionInfo = array( "Database"=>"FLP", "UID"=>"sa", "PWD"=>"p@ssw0rd", "CharacterSet" => "UTF-8");
	$conn = sqlsrv_connect( $serverName, $connectionInfo);
	if( isset($_POST['fboid']) )
	{
		$tempfboid =$_POST["fboid"];
		$_SESSION["dist"]= $tempfboid; 
	}
	$dist= $_SESSION["dist"];
	$fboname="";
	$_SESSION["dist"]=$dist;
	
	$pcc=0;
	$nmcc=0;
	$totalcc=0;
	
	if( $conn ) {
		$sql_getname = "Select * from tblappliciant where dist_id='" . $dist . "'";
		$stmtname = sqlsrv_query( $conn, $sql_getname);
		while( $row = sqlsrv_fetch_array( $stmtname, SQLSRV_FETCH_ASSOC) ) 
		{
			$fboname=$row['name'];
		}
		$sql_callsp = "Exec dbo.getDownLineOrder '" . $dist ."'";
		$stmt3 = sqlsrv_query( $conn, $sql_callsp);
		if( $stmt3 === false )
		{
			echo "Error in executing statement 3.\n";
			die( print_r( sqlsrv_errors(), true));
		}
		while( $row = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC) ) 
		{
			if($row['dist_id'] == $dist)
			{
				$pcc=$pcc+$row['total_cc'];			
				
			} else
			{
				$nmcc=$nmcc + $row['total_cc'];
			}
			$totalcc =$totalcc + $row['total_cc'];
      		
		}
		echo "箱積分（CC） - 5月份 </BR>";
		echo "FBO: " . $fboname . " (". $dist . " )" ."</BR></BR>";
		echo "Personal CC: <a href='CC_PDetails.php'>". $pcc . "</a></BR>";
		echo "Non-Manager CC:  <a href='CC_NMDetails.php'> ". $nmcc . "</a></BR>";
		echo "Total CC: ". $totalcc . "</BR>";
		
	}else{
		 echo "Connection could not be established.<br />";
		 die( print_r( sqlsrv_errors(), true));
	}
	// Close the connection.
	sqlsrv_close($conn);
?>
</body>
</html>