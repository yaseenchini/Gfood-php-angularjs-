<?php 
	include('common/functions.php');
	$data_access=new Data_access();
	$connect=$data_access->Connect_db();

	if(isset($_POST['tran_type'])){
		$tran_type=trim($_POST['tran_type']);
		$query="SELECT trans_flag_stock_in, trans_flag_stock_out FROM transaction_type WHERE id=".$tran_type;
		$execute_query=$data_access->Execute($query);
		$row=mysqli_fetch_object($execute_query);
		header("Content-Type: application/json; charset=UTF-8");
		$data = array('trans_flag_stock_in'=>$row->trans_flag_stock_in, 'trans_flag_stock_out'=>$row->trans_flag_stock_out);
    echo json_encode($data);
   	exit();
	}elseif(count($data=json_decode(file_get_contents("php://input")))>0){
		$btn_name = $data->btnName;
		if($btn_name == "Submit"){
			if(empty($data->tran_date)){
				$trans_date=date('Y-m-d');
			}else{
				$trans_date = date('Y-m-d', strtotime($data->tran_date));
			}
			$trans_type=$data->tran_type;
			
			if($trans_type==1){
				$trans_stock_nr_in=$data->tran_nr_in;
				$trans_in=$data->tran_in;
				$query="INSERT INTO transaction 
		            SET 
		            trans_date=?,
		            trans_type=?,
		            trans_stock_nr_in=?,
		            trans_in=?";
		    $stmt=$connect->prepare($query);
		    $stmt->bind_param("siid", $trans_date, $trans_type, $trans_stock_nr_in, $trans_in);
			}elseif($trans_type==2){
				$trans_stock_nr_in=$data->tran_nr_in;
				$trans_in=$data->tran_in;
				$trans_stock_nr_out=$data->tran_nr_out;
				$trans_out=$data->tran_out;
				$query="INSERT INTO transaction 
		            SET 
		            trans_date=?,
		            trans_type=?,
		            trans_stock_nr_in=?,
		            trans_stock_nr_out=?,
		            trans_in=?,
		            trans_out=?";
		    $stmt=$connect->prepare($query);
		    $stmt->bind_param("siiidd", $trans_date, $trans_type, $trans_stock_nr_in, $trans_stock_nr_out, $trans_in, $trans_out);
			}elseif($trans_type==3){
				$trans_stock_nr_out=$data->tran_nr_out;
				$trans_out=$data->tran_out;
				$query="INSERT INTO transaction				 
		            SET 
		            trans_date=?,
		            trans_type=?,
		            trans_stock_nr_out=?,
		            trans_out=?";
		    $stmt=$connect->prepare($query);
		    $stmt->bind_param("siid", $trans_date, $trans_type, $trans_stock_nr_out, $trans_out);
			}
			$execute_query=$stmt->execute();
	    if($execute_query==true){
	    	echo("success");
	    	exit();
	    }else{
	    	echo("error");
	    	exit();
	    }	
	  }elseif($btn_name == "Update"){
			$id = $data->id;
			$trans_type=$data->tran_type;
			if(empty($data->tran_date)){
				if($trans_type==1){
					$trans_stock_nr_in=$data->tran_nr_in;
					$trans_in=$data->tran_in;
					if(!empty($trans_stock_nr_in)){
						$query="UPDATE transaction 
				            SET 
				            trans_type=?,
				            trans_stock_nr_in=?,
				            trans_in=?
				            WHERE 
				            id=?";
					    $stmt=$connect->prepare($query);
					    $stmt->bind_param("iidi", $trans_type, $trans_stock_nr_in, $trans_in, $id);
					}else{
						$query="UPDATE transaction 
				            SET 
				            trans_type=?,
				            trans_in=?
				            WHERE 
				            id=?";
				    $stmt=$connect->prepare($query);
				    $stmt->bind_param("idi", $trans_type, $trans_in, $id);
					}
				}elseif($trans_type==2){
					$trans_stock_nr_in=$data->tran_nr_in;
					$trans_in=$data->tran_in;
					$trans_stock_nr_out=$data->tran_nr_out;
					$trans_out=$data->tran_out;
					if(!empty($trans_stock_nr_in) || !empty($trans_stock_nr_out)){
						$query="UPDATE transaction 
				            SET 
				            trans_type=?,
				            trans_stock_nr_in=?,
				            trans_stock_nr_out=?,
				            trans_in=?,
				            trans_out=?
				            WHERE 
				            id=?";
				    $stmt=$connect->prepare($query);
				    $stmt->bind_param("iiiddi", $trans_type, $trans_stock_nr_in, $trans_stock_nr_out, $trans_in, $trans_out, $id);
					}elseif(!empty($trans_stock_nr_in) && empty($trans_stock_nr_out)){
						$query="UPDATE transaction 
				            SET 
				            trans_type=?,
				            trans_stock_nr_in=?,
				            trans_in=?,
				            trans_out=?
				            WHERE 
				            id=?";
				    $stmt=$connect->prepare($query);
				    $stmt->bind_param("iiddi", $trans_type, $trans_stock_nr_in, $trans_in, $trans_out, $id);
					}elseif(empty($trans_stock_nr_in) && !empty($trans_stock_nr_out)){
						$query="UPDATE transaction 
				            SET 
				            trans_type=?,
				            trans_stock_nr_out=?,
				            trans_in=?,
				            trans_out=?
				            WHERE 
				            id=?";
				    $stmt=$connect->prepare($query);
				    $stmt->bind_param("iiddi", $trans_type, $trans_stock_nr_out, $trans_in, $trans_out, $id);
					}elseif(empty($trans_stock_nr_in) && empty($trans_stock_nr_out)){
						$query="UPDATE transaction 
				            SET 
				            trans_type=?,
				            trans_in=?,
				            trans_out=?
				            WHERE 
				            id=?";
				    $stmt=$connect->prepare($query);
				    $stmt->bind_param("iddi", $trans_type, $trans_in, $trans_out, $id);
					}
				}elseif($trans_type==3){
					$trans_stock_nr_out=$data->tran_nr_out;
					$trans_out=$data->tran_out;
					if(!empty($trans_stock_nr_out)){
						$query="UPDATE transaction				 
				            SET 
				            trans_type=?,
				            trans_stock_nr_out=?,
				            trans_out=?
				            WHERE 
				            id=?";
				    $stmt=$connect->prepare($query);
				    $stmt->bind_param("iidi", $trans_type, $trans_stock_nr_out, $trans_out, $id);
					}else{
						$query="UPDATE transaction				 
				            SET 
				            trans_type=?,
				            trans_out=?
				            WHERE 
				            id=?";
			    	$stmt=$connect->prepare($query);
			    	$stmt->bind_param("idi", $trans_type, $trans_out, $id);
					}
				}
			}else{
				$trans_date = date('Y-m-d', strtotime($data->tran_date));
				$trans_type=$data->tran_type;
				
				if($trans_type==1){
					$trans_stock_nr_in=$data->tran_nr_in;
					$trans_in=$data->tran_in;
					if(!empty($data->trans_stock_nr_in)){
						$query="UPDATE transaction 
				            SET 
				            trans_type=?,
				            trans_stock_nr_in=?,
				            trans_in=?
				            WHERE 
				            id=?";
					    $stmt=$connect->prepare($query);
					    $stmt->bind_param("iidi", $trans_type, $trans_stock_nr_in, $trans_in, $id);
					}else{
						$query="UPDATE transaction 
				            SET 
				            trans_type=?,
				            trans_in=?
				            WHERE 
				            id=?";
				    $stmt=$connect->prepare($query);
				    $stmt->bind_param("idi", $trans_type, $trans_in, $id);
					}
				}elseif($trans_type==2){
					$trans_stock_nr_in=$data->tran_nr_in;
					$trans_in=$data->tran_in;
					$trans_stock_nr_out=$data->tran_nr_out;
					$trans_out=$data->tran_out;
					if(!empty($trans_stock_nr_in) || !empty($trans_stock_nr_out)){
						$query="UPDATE transaction 
				            SET 
				            trans_type=?,
				            trans_stock_nr_in=?,
				            trans_stock_nr_out=?,
				            trans_in=?,
				            trans_out=?
				            WHERE 
				            id=?";
				    $stmt=$connect->prepare($query);
				    $stmt->bind_param("iiiddi", $trans_type, $trans_stock_nr_in, $trans_stock_nr_out, $trans_in, $trans_out, $id);
					}elseif(!empty($trans_stock_nr_in) && empty($trans_stock_nr_out)){
						$query="UPDATE transaction 
				            SET 
				            trans_type=?,
				            trans_stock_nr_in=?,
				            trans_in=?,
				            trans_out=?
				            WHERE 
				            id=?";
				    $stmt=$connect->prepare($query);
				    $stmt->bind_param("iiddi", $trans_type, $trans_stock_nr_in, $trans_in, $trans_out, $id);
					}elseif(empty($trans_stock_nr_in) && !empty($trans_stock_nr_out)){
						$query="UPDATE transaction 
				            SET 
				            trans_type=?,
				            trans_stock_nr_out=?,
				            trans_in=?,
				            trans_out=?
				            WHERE 
				            id=?";
				    $stmt=$connect->prepare($query);
				    $stmt->bind_param("iiddi", $trans_type, $trans_stock_nr_out, $trans_in, $trans_out, $id);
					}
				}elseif($trans_type==3){
					$trans_stock_nr_out=$data->tran_nr_out;
					$trans_out=$data->tran_out;
					if(!empty($trans_stock_nr_out)){
						$query="UPDATE transaction				 
				            SET 
				            trans_type=?,
				            trans_stock_nr_out=?,
				            trans_out=?
				            WHERE 
				            id=?";
				    $stmt=$connect->prepare($query);
				    $stmt->bind_param("iidi", $trans_type, $trans_stock_nr_out, $trans_out, $id);
					}else{
						$query="UPDATE transaction				 
				            SET 
				            trans_type=?,
				            trans_out=?
				            WHERE 
				            id=?";
			    	$stmt=$connect->prepare($query);
			    	$stmt->bind_param("idi", $trans_type, $trans_out, $id);
					}
				}
			}
			$execute_query=$stmt->execute();
	    if($execute_query==true){
	    	echo("success");
	    	exit();
	    }else{
	    	echo("error");
	    	exit();
	    }	
	  } 
	}elseif(isset($_GET['id'])){
		$id=trim($_GET['id']);
		$query="DELETE FROM transaction
						WHERE 
						id=?";
		$stmt=$connect->prepare($query);
	    $stmt->bind_param("i", $id);
		$execute_query=$stmt->execute();
		if($execute_query==true){
			echo("success");
			exit();
		}else{
			echo("error");
			exit();
		}
	}else{
		echo("some issue");
		exit();
	}
?>