<?php 
	include('common/functions.php');
	$data_access=new Data_access();
	$connect=$data_access->Connect_db();

	if(count($data=json_decode(file_get_contents("php://input")))>0){
		$query="SELECT * FROM transaction WHERE id>='0'";
		if(!empty($data->tran_type)){
			$query.=" AND trans_type='".$data->tran_type."'";
		}
		if(!empty($data->stock_type_plus)){
			$query.=" AND trans_stock_nr_in='".$data->stock_type_plus."'";
		}
		if(!empty($data->stock_type_minus)){
			$query.=" AND trans_stock_nr_out='".$data->stock_type_minus."'";
		}
		if(!empty($data->from_date)){
			$from_date = date('Y-m-d', strtotime($data->from_date.'+1 day'));
			$query.=" AND trans_date >= '".$from_date."'";
		}
		if(!empty($data->to_date)){
			$to_date = date('Y-m-d', strtotime($data->to_date.'+1 day'));
			$query.=" AND trans_date <='".$to_date."'";
		}
		if(!empty($data->sort_by)){
			$query.=" ORDER BY ".$data->sort_by." ASC";
		}

		$execute_query=$data_access->Execute($query);
		$display_transactions=array(); 
		while($row_fetch = mysqli_fetch_array($execute_query)){
			$query_trans="SELECT trans_description, trans_value FROM transaction_type WHERE id=$row_fetch[trans_type]";
      $execute_query_trans=$data_access->Execute($query_trans);
      $row_trans=mysqli_fetch_object($execute_query_trans);
      $row_fetch['trans_type']=$row_trans->trans_description;
      $row_fetch['trans_value']=$row_trans->trans_value;

      if($row_fetch['trans_stock_nr_in']!=""){
        $trans_stock_nr=$row_fetch['trans_stock_nr_in'];
        $query_trans="SELECT stock_description FROM stock_description WHERE id=$trans_stock_nr";
        $execute_query_trans=$data_access->Execute($query_trans);
        $row_trans=mysqli_fetch_object($execute_query_trans);
        $row_fetch['trans_stock_nr_in']=$row_trans->stock_description;
      }
      if($row_fetch['trans_stock_nr_out']!=""){
        $trans_stock_nr=$row_fetch['trans_stock_nr_out'];
        $query_trans="SELECT stock_description FROM stock_description WHERE id=$trans_stock_nr";
        $execute_query_trans=$data_access->Execute($query_trans);
        $row_trans=mysqli_fetch_object($execute_query_trans);
        $row_fetch['trans_stock_nr_out']=$row_trans->stock_description;
      }
      
			$display_transactions[] = $row_fetch; 
		}

		echo json_encode($display_transactions);  
		exit();
	}
?>