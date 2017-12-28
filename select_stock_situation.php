<?php 
  include('common/functions.php');
  $data_access=new Data_access();
  $connect=$data_access->Connect_db();
  
  $display_transactions=array();  
  $query_fetch="SELECT * FROM transaction";
  $data_result=$data_access->Execute($query_fetch);
  if(mysqli_num_rows($data_result)>0){  
    while($row_fetch = mysqli_fetch_assoc($data_result)){
      $query_trans="SELECT trans_description FROM transaction_type WHERE id=$row_fetch[trans_type]";
      $execute_query_trans=$data_access->Execute($query_trans);
      $row_trans=mysqli_fetch_object($execute_query_trans);
      $row_fetch['trans_type']=$row_trans->trans_description;

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
      $query_count="SELECT sum(trans_in) as sum_in  FROM transaction";

      $execute_query_count=$data_access->Execute($query_count);
      $row_count=mysqli_fetch_object($execute_query_count);
      $row_fetch['sum_in']=$row_count->sum_in;

      $query_count="SELECT sum(trans_out) as sum_out  FROM transaction";

      $execute_query_count_out=$data_access->Execute($query_count);
      $row_count_out=mysqli_fetch_object($execute_query_count_out);
      $row_fetch['sum_out']=$row_count_out->sum_out;
      $display_transactions[] = $row_fetch; 
       
    }
    echo json_encode($display_transactions);  
  }  
?>