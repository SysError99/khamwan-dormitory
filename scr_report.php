<?php
    require("scr_sql.php");
    require("scr_file.php");
    $message = "คุณไม่ได้รับอนุญาตให้ใช้งานส่วนนี้!";
    $query_error = "เตรียม Query ผิดพลาด โปรดลองใหม่อีกครั้ง หรือติดต่อผู้ดูแลระบบ";
    $next_page = "scr_auth.php";
    session_start();
    if(isset($_SESSION['mode'])){
        //user
        if($_SESSION['mode']===1){
            $next_page = "room_report_list.php";
            //add new report
            if(isset($_GET['add'])){
                if(isset($_POST['room']) && isset($_POST['detail']) && isset($_FILES['photo'])){
                    if(!empty($_POST['room'])){
                        //determine date + random number
                        $this_date = date("YmdHis"); for($i=0;$i<10;$i++){ $this_date.=rand(0,9); }
                        //determine file
                        $this_file = "/report_photos/".$this_date;
                        //file upload
                        $this_upload = file_upload($_FILES["photo"],$this_file);
                        switch($this_upload){
                            //success
                            default:
                                //add data
                                $query = mysqli_prepare($sql,"INSERT INTO tb_report VALUES(?,?,?,?,?)");
                                if(!$query){
                                    //query prepare error
                                    $message = $query_error;
                                }
                                else{
                                    //query
                                    $false = 0;
                                    mysqli_stmt_bind_param($query, 'sssss', $this_date, $_POST['room'], $_POST['detail'], $this_upload, $false);
                                    if(mysqli_stmt_execute($query)){
                                        $message = "เพิ่มข้อมูลเรียบร้อยแล้ว";
                                    }
                                    else{
                                        $message = $query_error;
                                    }
                                }
                                 break;
                            //photo does not exist
                            case "2":
                            case "5":
                                $message = "กรุณาอัปโหลดไฟล์รูปภาพ";
                                break;
                            //photo name conflict
                            case "3":
                                $message = "มีผู้ทำรายการอื่นกำลังดำเนินการอยู่ โปรดลองอีกครั้ง";
                                break;
                            //photo too big
                            case "4":
                                $message = "ไฟล์ภาพใหญ่เกินไป";
                            //unexpected error
                            case "1":
                                $message = "อัปโหลดผิดพลาด โปรดตรวจสอบการเชื่อมต่อ หรือแจ้งผู้ดูแลระบบ";
                                break;
                        }
                    }
                    else{
                        $message = "กรุณาระบุหมายเลขห้อง";
                    }
                }
                else{
                    $message = "ข้อมูลไม่ถูกต้อง";
                }
            }
            //fixed
            else if(isset($_GET['fix'])){
                if(isset($_GET['date'])){
                    $query = mysqli_prepare($sql,"DELETE FROM tb_report WHERE report_date=?");
                    if(!$query){
                        //query prepare error
                        $message = $query_error;
                    }
                    else{
                        //query
                        $false = 0;
                        mysqli_stmt_bind_param($query, 's',$_GET['date']);
                        if(mysqli_stmt_execute($query)){
                            $message = "ลบข้อมูลเรียบร้อยแล้ว";
                        }
                        else{
                            $message = $query_error;
                        }
                    }
                }
                else{
                    $message = "พารามิเตอร์ไม่ถูกต้อง";
                }
            }
            else{
                $message = "คำสั่งไม่ถูกต้อง";
            }
        }
        //admin
        else if($_SESSION['mode']===2){
            $next_page = "report_list.php";
            //fixed
            if(isset($_GET['fix'])){
                $query = mysqli_prepare($sql,"UPDATE tb_report SET report_fix=1 WHERE report_date=?");
                if(!$query){
                    //query prepare error
                    $message = $query_error;
                }
                else{
                    //query
                    mysqli_stmt_bind_param($query, 's',$_GET['date']);
                    if(mysqli_stmt_execute($query)){
                        $message = "อัปเดตข้อมูลเรียบร้อยแล้ว";
                    }
                    else{
                        $message = $query_error;
                    }
                }
            }
            else{
                $message = "คำสั่งไม่ถูกต้อง";
            }
        }
    }
    echo "<script>alert('".$message."');window.open('".$next_page."','_self');</script>";
?>
