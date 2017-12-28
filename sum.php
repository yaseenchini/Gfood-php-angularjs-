<?php 
  include('common/functions.php');
  $data_access=new Data_access();
  $connect=$data_access->Connect_db();
  $total= 0;

  $query_fetch="SELECT sum(trans_in) as sum_in  FROM transaction";
  $data_result=$data_access->Execute($query_fetch);
  while($row_trans=mysqli_fetch_object($data_result)){
  	$total = $row_trans->sum_in;
  }
  echo $total;

?>