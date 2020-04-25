<?php
    require("scr_sql.php");
    session_start();
    $message = "";
    $query_error = "Query ผิดพลาด";
    if(isset($_SESSION['mode'])){
        if($_SESSION['mode'] === 2){
            //add mode
            if(isset($_GET['add'])){
                if(!empty($_POST['id']) && !empty($_POST['type'])){
                    $pwd = "";
                    if(!empty($_POST['pwd'])){
                        //set password (if exists)
                        $pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
                    }
                    $query = mysqli_prepare($sql, "INSERT INTO tb_room VALUES(?,?,?,?,?,?)");
                    if(!$query){
                        $message = $query_error;
                    }
                    else{
                        //bind params
                        mysqli_stmt_bind_param($query, 'ssssss', $_POST['id'], $pwd, $_POST['type'], $_POST['owner'], $_POST['addr'], $_POST['phone']);
                        //start query
                        if(mysqli_stmt_execute($query)){
                            $message = "เพิ่มข้อมูลห้องเรียบร้อยแล้ว";
                        }
                        else{
                            $message = "เพิ่มข้อมูลห้องล้มเหลว";
                        }
                    }
                }
                else{
                    $message = "กรุณาระบุหมายเลขห้อง และชนิดของการเช่า (อะไรก็ได้)";
                }
            }
            //edit mode
            else if(isset($_GET['edit'])){
                if(!empty($_POST['id']) && !empty($_POST['type']) && !empty($_POST['owner'])){
                    $query = NULL;
                    $pwd = "";
                    if(!empty($_POST['pwd'])){
                        //set password
                        $pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
                        $query = mysqli_prepare($sql, "UPDATE tb_room SET room_pwd=?,room_type=?,room_owner=?,room_owner_addr=?,room_owner_phone=? WHERE room_id=?");
                    }
                    else{
                        //don't set password
                        $query = mysqli_prepare($sql, "UPDATE tb_room SET room_type=?,room_owner=?,room_owner_addr=?,room_owner_phone=? WHERE room_id=?");
                    }
                    if(!$query){
                        $message = $query_error;
                    }
                    else{
                        //bind params
                        if(!empty($_POST['pwd'])){
                            //set password
                            mysqli_stmt_bind_param($query, 'ssssss', $pwd, $_POST['type'], $_POST['owner'], $_POST['addr'], $_POST['phone'], $_POST['id']);
                        }
                        else{
                            //don't set password
                            mysqli_stmt_bind_param($query, 'sssss',  $_POST['type'], $_POST['owner'], $_POST['addr'], $_POST['phone'], $_POST['id']);
                        }
                        //start query
                        if(mysqli_stmt_execute($query)){
                            $message = "แก้ไขข้อมูลเรียบร้อยแล้ว";
                        }
                        else{
                            $message = "แก้ไขข้อมูลล้มเหลว";
                        }
                    }
                }
                else{
                    $message = "กรุณากรอกข้อมูลอย่างน้อย หมายเลขห้องที่จอง ชนิดห้อง เจ้าของห้อง และรหัสผ่าน";
                }
            }
            //cancel mode
            else if(isset($_GET['cancel'])){
                if(isset($_GET['id'])){
                    $empty = "";
                    $query = mysqli_prepare($sql, "UPDATE tb_room SET room_owner=?,room_owner_addr=?,room_owner_phone=? WHERE room_id=?");
                    if(!$query){
                        $message = $query_error;
                    }
                    else{
                        //bind params
                        mysqli_stmt_bind_param($query, 'ssss', $empty, $empty, $empty, $_GET['id']);
                        if(mysqli_stmt_execute($query)){
                            $message = "ยกเลิกสัญญาเรียบร้อยแล้ว";
                        }
                        else{
                            $message = "ยกเลิกสัญญาล้มเหลว";
                        }
                    }
                }
                else{
                    //room_id not specified
                    $message = $query_error;
                }
            }
            //delete mode
            else if(isset($_GET['delete'])){
                if(isset($_GET['id'])){
                    $query = mysqli_prepare($sql, "DELETE FROM tb_room WHERE room_id=?");
                    if(!$query){
                        $message = "$query_error";
                    }
                    else{
                        mysqli_stmt_bind_param($query, 's', $_GET['id']);
                        if(mysqli_stmt_execute($query)){
                            $message = "ลบข้อมูลเรียบร้อยแล้ว";
                        }
                        else{
                            $message = "ลบข้อมูลล้มเหลว";
                        }
                    }
                }
                else{
                    //room_id not specified
                    $message = $query_error;
                }
            }
            //unknown
            else{
                $message = "ดำเนินการไม่ถูกต้อง";
            }
        }
        else{$message = "คุณไม่ได้รับอนุญาตให้เข้าถึงส่วนนี้";}
    }
    else{$message = "กรุณาล็อกอินก่อนเข้าใช้งาน";}
    echo "<script>alert('".$message."');window.open('room_list.php','_self');</script>";
    mysqli_close($sql);
?>
