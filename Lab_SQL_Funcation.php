<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php
	
	$serverName = "172.23.4.250"; //serverName\instanceName
	$connectionInfo = array( "Database"=>"FLPNDP", "UID"=>"sa", "PWD"=>"p@ssw0rd", "CharacterSet" => "UTF-8");
	$conn = sqlsrv_connect( $serverName, $connectionInfo);
	$pmcc=0;
	if( $conn ) {
		
		$sql_getpcc = "select SUM(total_cc) as nmcc from dbo.GetNonManagerOrder(?)";
		$params=array("852000000245");
		$result = sqlsrv_query( $conn, $sql_getpcc,$params);
		if ($result === false )
		{
			die( print_r( sqlsrv_errors(), true));
		}else
		{
			 $obj = sqlsrv_fetch_object( $result );
			 $pmcc= $obj->nmcc;
			 echo $pmcc . "<BR/>";
			 $pmcc +=1;
			 echo $pmcc . "<BR/>";
			
		}
		
	}else{
		 echo "Connection could not be established.<br />";
		 die( print_r( sqlsrv_errors(), true));
	}
	// Close the connection.
	sqlsrv_close($conn);
?>
</body>
</html>