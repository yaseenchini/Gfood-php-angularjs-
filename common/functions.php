<?php session_start(); ?>
<?php 
	class Data_access{
		public function Connect_db(){
			$hostname_connect="localhost";
			$username_connect="root";
			$password_connect="";
			$database_connect="gfoodinventory";

			$connect=mysqli_connect($hostname_connect, $username_connect, $password_connect, $database_connect);
			return $connect;
		}
		public function Execute($query){
			$connect=$this->Connect_db();
			$execute_query=mysqli_query($connect, $query) or 
			die(mysqli_error($connect));
			return $execute_query;
		}
		public function Redirect($msg, $msg_type, $url){
			$_SESSION['msg']=$msg;
			$_SESSION['msg_type']=$msg_type;
			header("location: $url");
			exit();
		}
		public function Show_msg(){
			echo("<div id='$_SESSION[msg_type]'>$_SESSION[msg]</div>");
			$_SESSION['msg']=Null;
			$_SESSION['msg_type']=Null;
		}
		public function Check_user_name($username){
      $query="SELECT user_name FROM users 
              WHERE 
              user_name=?";
      $connect=$this->Connect_db();
      $stmt=$connect->prepare($query);
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result=$stmt->get_result();
      $num_of_rows = $result->num_rows;
      $stmt->close();
      $connect->close();
      return $num_of_rows;
		}
		public function Check_user_disability($username, $password){
      $query="SELECT * FROM users 
              WHERE 
              user_name=?
              AND
              user_password=? 
              AND 
              user_disabled='1'";
      $connect=$this->Connect_db();
      $stmt=$connect->prepare($query);
      $stmt->bind_param("ss", $username, $password);
      $stmt->execute();
      $result=$stmt->get_result();
      $num_of_rows = $result->num_rows;
      $stmt->close();
      $connect->close();
      return $num_of_rows;
		}
		public function Increament_attempt($username){
			$query="UPDATE users 
					    SET 
					    user_attempt=user_attempt+1
					    WHERE
					    user_name=?";
			$connect=$this->Connect_db();
      $stmt=$connect->prepare($query);
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $stmt->close();
      $connect->close();
		}
		public function Block_user($username){
			$query="UPDATE users 
					    SET 
					    user_attempt=0,
              user_disabled=1
					    WHERE
					    user_name=?";
			$connect=$this->Connect_db();
      $stmt=$connect->prepare($query);
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $stmt->close();
      $connect->close();
		}
		public function Get_user_attempts($username){
			$query="SELECT * FROM users WHERE user_name=?";
			$connect=$this->Connect_db();
      $stmt=$connect->prepare($query);
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result=$stmt->get_result();
      $row=$result->fetch_object();
      return $row->user_attempt;
		}
		function Decrease_attempts($user_id){
			$query="UPDATE users SET user_attempt='0' WHERE id='$user_id'";
			$execute_query=$this->Execute($query);
		}

		public function Upload_file($file_name, $tmp_name, $folder, $prefix){
			$new_file_name=$prefix.time().rand(11111,9999).strtolower(strrchr($file_name, '.'));
			$file_exists=$folder.$new_file_name;

			if(file_exists($file_exists)){
				Upload($new_file_name, $tmp_name, $folder, $prefix);
			}else{
				
				$Upload_file=move_uploaded_file($tmp_name, $file_exists);
				chmod($file_exists, 0644);
			}

			if($Upload_file==true){
				return $new_file_name;
			}else{
				return false;
			}
		}
		public function Upload($file_name, $tmp_name, $folder, $prefix){
			$new_file_name=$prefix.time().rand(11111,9999).strtolower(strrchr($file_name, '.'));
			$file_exists=$folder.$new_file_name;

			if(file_exists($file_exists)){
				Upload($new_file_name, $tmp_name, $folder, $prefix);
			}else{
				// chmod("/var/www/files/uploads/" . $_FILES["datei"]["name"] . ".jpg", 0777)
				$Upload_file=move_uploaded_file($tmp_name, $file_exists);
			}

			if($Upload_file==true){
				return $new_file_name;
			}else{
				return false;
			}
		}
		public function Draw_combo($crs_record_id, $course_name){
			$query="SELECT * FROM courses";
			$execute_query=$this->Execute($query);
			echo("<select name='$course_name' class='form-control'>");
				while($rows=mysqli_fetch_object($execute_query)){
					$crs_id=$rows->crs_id;
					$crs_name=$rows->crs_name;
					if($crs_record_id==$crs_id){
						$selected="selected='selected'";
					}else{
						$selected="";
					}
					echo("<option value='$crs_id' $selected >$crs_name</option>");
				}
			echo("</select>");
		}
		public function Send_email($to, $subject, $msg, $header){
			mail($to, $subject, $msg, $header);
		}
		public function Search_file($field, $table, $id,  $record_id, $folder){
			$query="SELECT $field FROM $table WHERE $id='$record_id'";
			$execute_query=$this->Execute($query);
			$row=mysqli_fetch_object($execute_query);
			$file=$row->$field;
			$file_exists="$folder/$file";
			if(file_exists($file_exists)){
				unlink($file_exists);
			}else{
				return false;
			}		
		}
	}
?>