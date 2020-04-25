<?php require("scr_sql.php");
$login_success = 0;
$login_mode = 2;
$message="เกิดข้อผิดพลาดที่ไม่คาดคิด โปรดแจ้งผู้ดูและระบบ หรือติดต่อผู้พัฒนาซอฟต์แวร์";
//username & password must not be empty
if(!empty($_POST["username"]) && !empty($_POST["password"])){
    while($login_mode > 0){
        //login_mode
        $query_cmd = "";
        switch($login_mode){
            //admin-mode
            case 2:
                $query_cmd = "SELECT admin_usr,admin_pwd,admin_level from tb_admin WHERE admin_usr=?";
                break;
            //user (room) mode
            case 1:
                $query_cmd = "SELECT room_id,room_pwd,room_type from tb_room WHERE room_id=?";
                break;
        }
        //prepare query
        $query = mysqli_prepare($sql,$query_cmd);
        if(!$query){
            //query prepare error
            $message = "เตรียม Query ผิดพลาด โปรดลองใหม่อีกครั้ง หรือติดต่อผู้ดูแลระบบ";
        }
        else{
            //find username
            mysqli_stmt_bind_param($query, 's', $_POST['username']);
            if(mysqli_stmt_execute($query)){
                mysqli_stmt_store_result($query);
                mysqli_stmt_bind_result($query, $usr, $pwd, $lvl);
                if(mysqli_stmt_num_rows($query) > 0){
                    //find password
                    mysqli_stmt_fetch($query);
                    if(password_verify($_POST["password"],$pwd)){
                        //complete auth
                        session_start();
                        session_regenerate_id();
                        $_SESSION['usr'] = $usr;
                        $_SESSION['mode']= $login_mode;
                        $login_success = 1;
                        //admin-level
                        if($login_mode===2){
                            $_SESSION['lvl'] = $lvl;
                        }
                        $login_mode = 0;
                        $message = "เข้าสู่ระบบสำเร็จ";
                    }
                    else{
                        //incomplete auth
                        $message = "รหัสผ่านไม่ถูกต้อง";
                        $login_mode = 0;
                    }
                }
                else{
                    //no user exist
                    $message = "ชื่อผู้ใช้ไม่ถูกต้อง";
                }
                mysqli_stmt_free_result($query);
                mysqli_stmt_close($query);
            }
            else{
                //execute error
                $message = "เกิดข้อผิดพลาดบนเซิร์ฟเวอร์";
                $login_mode = 0;
            }
        }
        //switch login mode
        $login_mode--;
    }
}
else {
    $message = "กรุณาระบุชื่อผู้ใช้และรหัสผ่าน";
}
echo "<script>success=".$login_success.";nextpage='login.html';alert('".$message."');if(success==1){nextpage='scr_auth.php';}window.open(nextpage,'_self');</script>";
mysqli_close($sql);?>
