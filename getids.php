<?php 
	include('common/functions.php');
	$data_access=new Data_access();
	$connect=$data_access->Connect_db();

	if(count($data=json_decode(file_get_contents("php://input")))>0){
		$id = $data->id;
		$display_transactions=array();  
	  $query_fetch="SELECT * FROM transaction WHERE id=".$id;
	  $data_result=$data_access->Execute($query_fetch);
	  if(mysqli_num_rows($data_result)>0){  
	  	$row_fetch = mysqli_fetch_object($data_result);
	  	$display_transactions[] = $row_fetch; 
	    echo json_encode($display_transactions);  
	  }
	}
?>