<?php
    include('common/functions.php');
    $data_access=new Data_access();
    $connect=$data_access->Connect_db();
    if(isset($_POST['user_login'])){
        if(!empty($_POST["username"]) && !empty($_POST["password"])){
            $username = ucfirst(trim($_POST["username"]));
            $num_of_rows=$data_access->Check_user_name($username);
            if($num_of_rows<1){
                $msg="Invalid Username";
                $msg_type="error";
                $url="login.php";
                $data_access->Redirect($msg, $msg_type, $url);
                exit();
            }else{
                $password=ucfirst(trim($_POST["password"]));
                $query="SELECT * FROM users 
                        WHERE 
                        user_name=?
                        AND
                        user_password=? 
                        AND 
                        user_disabled='0'";
                $stmt=$connect->prepare($query);
                $stmt->bind_param("ss", $username, $password);
                $stmt->execute();
                $result=$stmt->get_result();
                $num_of_rows = $result->num_rows;
                if($num_of_rows>0){
                    $row=$result->fetch_object();
                    $_SESSION['user_id']=$row->id;
                    $_SESSION['user_name']=$row->user_name;
                    $_SESSION['user_role']=$row->user_menu_access;
                    $msg="";
                    $msg_type="";
                    $url="index.php";
                    $stmt->close();
                    $connect->close();
                    $data_access->Redirect($msg, $msg_type, $url);
                    exit();
                }else{
                    $password=ucfirst(trim($_POST["password"]));
                    $num_of_rows=$data_access->Check_user_disability($username, $password);
                    if($num_of_rows>0){
                        $msg="Your account is temporarily blocked";
                        $msg_type="error";
                        $url="login.php";
                        $data_access->Redirect($msg, $msg_type, $url);
                        exit();
                    }
                    $user_attempt=$data_access->Get_user_attempts($username);
                    if($user_attempt<5){
                        $data_access->Increament_attempt($username);
                        $remaining=4-$user_attempt;
                        if($user_attempt==4){
                            $msg="Your account is temporarily blocked";
                        }else{
                            $msg="Invalid Credentials remaining attempts <strong>".$remaining."</strong>";
                        }
                        $msg_type="error";
                        $url="login.php";
                        $stmt->close();
                        $connect->close();
                        $data_access->Redirect($msg, $msg_type, $url);
                        exit();
                    }else{
                        $data_access->Block_user($username);
                        $msg="Your account is temporarily blocked";
                        $msg_type="error";
                        $url="login.php";
                        $stmt->close();
                        $connect->close();
                        $data_access->Redirect($msg, $msg_type, $url);
                        exit();
                    }
                }
            }
        }else{
            $msg="Username/Password Required";
            $msg_type="info";
            $url="login.php";
            $data_access->Redirect($msg, $msg_type, $url);
            exit();
        }
    }else{
        $msg="";
        $msg_type="";
        $url="404.html";
        $data_access->Redirect($msg, $msg_type, $url);
        exit();
    }
?>