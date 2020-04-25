<?php 
    require("scr_sql.php");
    require("scr_file.php");
    $message = "";
    $next_page = "index.html";
    //add
    if(isset($_GET['add'])){
        //complete data?
        $next_page="reserve.php";
        if(!empty($_POST["name"].$_POST["phone"].$_POST["room"])){
            //determine date + random number
            $this_date = date("YmdHis"); for($i=0;$i<10;$i++){ $this_date.=rand(0,9); }
            //determine file
            $this_file = "/reserve_receipts/".$this_date;
            //file upload
            $this_upload = file_upload($_FILES["form_file"],$this_file);
            switch($this_upload){
                //success
                default:
                    //add data
                    $query = mysqli_prepare($sql,"INSERT INTO tb_reserve SELECT * FROM (SELECT ? AS date,? AS name,? AS phone,? AS room,? AS type,? AS receipt) AS tmp WHERE NOT EXISTS (SELECT room from tb_reserve WHERE room=?) LIMIT 1;"); //protect against data duplication
                    if(!$query){
                        //query prepare error
                        $message = "เตรียม Query ผิดพลาด โปรดลองใหม่อีกครั้ง หรือติดต่อผู้ดูแลระบบ";
                    }
                    else{
                        //query
                        mysqli_stmt_bind_param($query, 'sssssss', $this_date, $_POST['name'], $_POST['phone'], $_POST['room'], $_POST['type'], $this_upload, $_POST['room']);
                        if(mysqli_stmt_execute($query)){
                            $message = "เพิ่มข้อมูลเรียบร้อยแล้ว";
                        }
                        else{
                            $message = "Query ผิดพลาด โปรดลองใหม่อีกครั้ง หรือติดต่อผู้ดูแลระบบ";
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
        //insufficient data
        else{
            $message = "ข้อมูลไม่ครบถ้วน กรุณาลองใหม่อีกครั้ง";
        }
    }
    //remove
    else if(isset($_GET['remove'])){
        session_start();
        $message = "คุณไม่ได้รับอนุญาตให้เข้าถึงส่วนนี้";
        if(isset($_SESSION['mode'])){
            if($_SESSION['mode'] === 2){ //requires admin
                $next_page = "reserve_list.php";
                if(isset($_GET['date'])){
                    $query = mysqli_prepare($sql,"DELETE FROM tb_reserve WHERE date=?");
                    if(!$query){
                        $message = "Query ผิดพลาด";
                    }
                    else{
                        mysqli_stmt_bind_param($query, 's', $_GET['date']);
                        if(mysqli_stmt_execute($query)){
                            $message = "ลบข้อมูลเรียบร้อยแล้ว";
                            $files = glob(__DIR__ . "/reserve_receipts/".$_GET['date']."*");
                            foreach($files as $file){
                                if(is_file($file)) {
                                    if(!unlink($file)){
                                        $message = "ไม่สามารถลบไฟล์สลิปออกจากระบบได้";
                                    }
                                }
                            }
                        }
                    }
                }
                else{
                    $message = "ข้อมูลไม่ถูกต้อง";
                }
            }
        }
    }
    echo "<script>alert('".$message."');window.open('".$next_page."','_self');</script>";
$sql->close();?>
