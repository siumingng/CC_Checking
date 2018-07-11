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
	$connectionInfo = array( "Database"=>"FLPNDP", "UID"=>"sa", "PWD"=>"p@ssw0rd", "CharacterSet" => "UTF-8");
	$conn = sqlsrv_connect( $serverName, $connectionInfo);
	if( isset($_POST['fboid']) )
	{
		$tempfboid =$_POST["fboid"];
		$_SESSION["dist"]= $tempfboid; 
	}
	$dist= $_SESSION["dist"];
	$fboname="";
	$_SESSION["dist"]=$dist;
	
	$lmpcc=0;
	$lmnmcc=0;
	$lmtotalcc=0;
	
	$cmpcc=0;
	$cmnmcc=0;
	$cmtotalcc=0;
	
	if( $conn ) {
		$sql_getname = "Select * from tblappliciant where dist_id='" . $dist . "'";
		$stmtname = sqlsrv_query( $conn, $sql_getname);
		while( $row = sqlsrv_fetch_array( $stmtname, SQLSRV_FETCH_ASSOC) ) 
		{
			$fboname=$row['name'];
		}
		
		//Last Month PCC
		$sql_get_lm_pcc = "select SUM(total_cc) as pcc from GetLastMonthPOrder(?)";
		$params=array($dist);
		$result = sqlsrv_query( $conn, $sql_get_lm_pcc,$params);
		if ($result === false )
		{
			die( print_r( sqlsrv_errors(), true));
		}else
		{
			 $obj = sqlsrv_fetch_object( $result );
			 $lmpcc= $obj->pcc;			
			
		}
		
		//Last Month NMCC
		$sql_get_lm_nmcc = "select SUM(total_cc) as nmcc from GetLastMonthNMOrder(?)";
		$params=array($dist);
		$result = sqlsrv_query( $conn, $sql_get_lm_nmcc,$params);
		if ($result === false )
		{
			die( print_r( sqlsrv_errors(), true));
		}else
		{
			 $obj = sqlsrv_fetch_object( $result );
			 $lmnmcc= $obj->nmcc;			
			
		}
		
		//Last Month Total CC
		$lmtotalcc = $lmpcc + $lmnmcc;
		
		//Current Month PCC
		$sql_get_cm_pcc = "select SUM(total_cc) as pcc from GetCurrentMonthPOrder(?)";
		$params=array($dist);
		$result = sqlsrv_query( $conn, $sql_get_cm_pcc,$params);
		if ($result === false )
		{
			die( print_r( sqlsrv_errors(), true));
		}else
		{
			 $obj = sqlsrv_fetch_object( $result );
			 $cmpcc= $obj->pcc;			
			
		}
		
		//Current Month NMCC
		$sql_get_cm_nmcc = "select SUM(total_cc) as nmcc from GetCurrentMonthNMOrder(?)";
		$params=array($dist);
		$result = sqlsrv_query( $conn, $sql_get_cm_nmcc,$params);
		if ($result === false )
		{
			die( print_r( sqlsrv_errors(), true));
		}else
		{
			 $obj = sqlsrv_fetch_object( $result );
			 $cmnmcc= $obj->nmcc;			
			
		}
		
		//Current Month Total CC
		$cmtotalcc = $cmpcc + $cmnmcc;
		
		
		echo "箱積分（CC）</BR>";
		echo "FBO: " . $fboname . " (". $dist . " )" ."</BR></BR>";
		echo "Last Month Personal CC: <a href='CC_PDetails.php'>". $lmpcc . "</a></BR>";
		echo "Last Month Non-Manager CC: <a href='CC_PDetails.php'>". $lmnmcc . "</a></BR>";
		echo "Last Month Total CC: <a href='CC_PDetails.php'>". $lmtotalcc . "</a></BR>";
		echo "</BR>";
		echo "Current Month Personal CC: <a href='CC_PDetails.php'>". $cmpcc . "</a></BR>";
		echo "Current Month Non-Manager CC: <a href='CC_PDetails.php'>". $cmnmcc . "</a></BR>";
		echo "Current Month Total CC: <a href='CC_PDetails.php'>". $cmtotalcc . "</a></BR>";
		
		
	}else{
		 echo "Connection could not be established.<br />";
		 die( print_r( sqlsrv_errors(), true));
	}
	// Close the connection.
	sqlsrv_close($conn);
?>
</body>
</html>