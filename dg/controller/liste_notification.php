<?php 
//ini_set("display_errors",0); 
ini_set('max_execution_time', 5000);

function liste_notification()
{
				include('../../../cnx/database.php');
				$pdo = Database1::connect();
				$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$pdo->exec('SET NAMES utf8');
				
			
				
$sql = "
SELECT 
body
,dateinsert
,sender 
,id
,status
FROM notification 
";
$result = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
$return = array();
foreach ($result as $row1) 
{
		
     $return[]=array('body'=>$row1['body'],
					'dateinsert'=>$row1['dateinsert'],
					'sender'=>$row1['sender'],
					'id'=>$row1['id'],
					'status'=>$row1['status'],
					);
}

header('Content-type: application/json');
echo json_encode($return);
Database::disconnect();
}
?>