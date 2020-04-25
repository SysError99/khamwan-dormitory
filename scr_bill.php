<?php
    require("scr_sql.php");
    require("scr_file.php");
    require("scr_document.php");
    
    function parameters(){
        return (isset($_GET['date']) && isset($_GET['room']) && isset($_GET['nrg']) && isset($_GET['nrgp']) && isset($_GET['water']) && isset($_GET['waterp']) && isset($_GET['price']));
    }
    
    $message = "";
    $message_html = false;
    $query_error = "Query ผิดพลาด";
    $metadata_error = "ไม่สามารถใช้งานไฟล์ข้อมูลเซิร์ฟเวอร์ได้   โปรดติดต่อผู้พัฒนาซอฟต์แวร์!";
    $unknown_cmd = "คำสั่งไม่ถูกต้อง";
    $empty = "";
    $next_page = "";
    session_start();
    if(isset($_SESSION['mode'])){
        //invoice
        if(isset($_GET['invoice'])){
            if(isset($_GET['file'])){
                $next_page = $_GET['file'];
            }else{
                $message = "ไม่พบเส้นทางที่ต้องการไป";
            }
        }
        //admin
        else if($_SESSION['mode'] === 2){
            $next_page = "bill_list.php";
            //add
            if(isset($_GET['add'])){
                //random date
                $date = date("YmdHis"); for($i=0;$i<10;$i++){ $date.=rand(0,9); }
                $query = mysqli_prepare($sql, "INSERT INTO tb_bill VALUES(?,?,?,?,?,?,?,?,?,?)");
                if(!$query){
                    $message = $query_error;
                }
                else{
                    //calculate
                    $nrg = doubleval($_POST['nrg']);
                    $nrgp= doubleval($_POST['nrg_price']);
                    $water = doubleval($_POST['water']);
                    $waterp= doubleval($_POST['water_price']);
                    $price = doubleval($_POST['price']);
                    //submit
                    mysqli_stmt_bind_param($query, 'ssdddddsss', $date, $_POST['id'], $nrg, $nrgp, $water, $waterp, $price, $empty, $empty, $empty);
                    if(mysqli_stmt_execute($query)){
                        //set to step 2
                        $next_page = "scr_bill.php?add0&date=".$date."&room=".$_POST['id']."&nrg=".$_POST['nrg']."&nrgp=".$_POST['nrg_price']."&water=".$_POST['water']."&waterp=".$_POST['water_price']."&price=".$_POST['price'];
                    }
                    else{
                        $message = $query_error;
                    }
                }
            }
            //add step 2 (save the document)
            else if(isset($_GET['add0'])){
                if(!(parameters())){
                    $message = "พารามิเตอร์ชั้นตอนที่ 2 ไม่ถูกต้อง";
                }
                else{
                    //get room data
                    $query = mysqli_prepare($sql, "SELECT room_owner,room_owner_addr FROM tb_room WHERE room_id=?");
                    if(!$query){
                        $message = $query_error;
                    }
                    else{
                        mysqli_stmt_bind_param($query, 's', $_GET['room']);
                        if(!mysqli_stmt_execute($query)){
                            $message = $query_error;
                        }else{
                            //fetch
                            mysqli_stmt_store_result($query);
                            mysqli_stmt_bind_result($query, $owner, $addr);
                            if(mysqli_stmt_num_rows($query)>0){
                                mysqli_stmt_fetch($query);
                            }
                            //read metadata
                            $data = document_server_data_read();
                            $data_error = "ไฟล์ข้อมูลเซิร์ฟเวอร์ผิดพลาด";
                            if(!$data){
                                $message = "อ่าน".$data_error;
                            }
                            else if($data->taxid==="0123456789"){
                                $message = "กรุณาตั้งค่าข้อมูลหอพักให้ถูกต้อง";
                            }
                            else{
                                //count
                                $data->invoiceno++;
                                //save to metadata
                                if(!document_server_data_write($data)){
                                    $message = $metadata_error;
                                }
                                else{
                                    //decount
                                    $data->invoiceno--;
                                    //html
                                    $invoice = document_header();
                                    $invoice.= document_title_invoice();
                                    $invoice.= document_info("เลขที่ใบแจ้งหนี้    ".$data->invoiceno, "ใบแจ้งหนี้/ใบกำกับภาษี", $data->name,$data->address,$data->phone,$data->taxid,$owner,$addr,$_GET['date']);
                                    $invoice.= document_table($_GET['room'], $_GET['water'],$_GET['waterp'],$_GET['nrg'],$_GET['nrgp'],$_GET['price']);
                                    $invoice.= document_footer_invoice();
                                    $invoice.= document_print();
                                    $invoice.= document_footer();
                                    //save
                                    $invoice_file_target = "/invoices/invoice-".$data->invoiceno.".html";
                                    $invoice_file_location = __DIR__ . $invoice_file_target;
                                    $invoice_file = fopen($invoice_file_location,"x");
                                    if(!$invoice_file){
                                        $message .= "ไม่สามารถสร้างไฟล์ใบแจ้งหนี้ได้";
                                    }else{
                                        fwrite($invoice_file, $invoice);
                                        fclose($invoice_file);
                                        //set to step 3
                                        $next_page = "scr_bill.php?add1&date=".$_GET['date']."&file=".$invoice_file_target;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            else if(isset($_GET['add1'])){
                if(!(isset($_GET['date']) && isset($_GET['file']))){
                    $message = "พารามิเตอร์ชั้นตอนที่ 3 ไม่ถูกต้อง";
                }else{
                    $query = mysqli_prepare($sql, "UPDATE tb_bill SET bill_invoice=? WHERE bill_date=?");
                    if(!$query){
                        $message = $query_error;
                    }
                    else{
                        mysqli_stmt_bind_param($query, 'ss', $_GET['file'], $_GET['date']);
                        if(!mysqli_stmt_execute($query)){
                            $message = $query_error;
                        }else{
                            $message = "เพิ่มข้อมูลเรียบร้อยแล้ว";
                        }
                    }
                }
            }
            //invalid (receipt)
            else if(isset($_GET['invalid'])){
                $query = mysqli_prepare($sql, "UPDATE tb_bill SET bill_paydate='',bill_receipt='' WHERE bill_date=?");
                if(!$query){
                    $message = $query_error;
                }
                else{
                    mysqli_stmt_bind_param($query, 's', $_GET['date']);
                    if(mysqli_stmt_execute($query)){
                        $message = "ดำเนินการเรียบร้อยแล้ว";
                    }
                    else{
                        $message = $query_error;
                    }
                }
            }
            //tax (invoice)
            else if(isset($_GET['tax'])){
                if(!(parameters() && isset($_GET['paydate']))){
                    $message = "พารามิเตอร์ไม่ถูกต้อง";
                }
                else{
                    //get room data
                    $query = mysqli_prepare($sql, "SELECT room_owner,room_owner_addr FROM tb_room WHERE room_id=?");
                    if(!$query){
                        $message = $query_error;
                    }
                    else{
                        mysqli_stmt_bind_param($query, 's', $_GET['room']);
                        if(!mysqli_stmt_execute($query)){
                            $message = $query_error;
                        }else{
                            //fetch
                            mysqli_stmt_store_result($query);
                            mysqli_stmt_bind_result($query, $owner, $addr);
                            if(mysqli_stmt_num_rows($query)>0){
                                mysqli_stmt_fetch($query);
                            }
                            //read metadata
                            $data = document_server_data_read();
                            $data_error = "ไฟล์ข้อมูลเซิร์ฟเวอร์ผิดพลาด";
                            if(!$data){
                                $message = "อ่าน".$data_error;
                            }
                            else if($data->taxid==="0123456789"){
                                $message = "กรุณาตั้งค่าข้อมูลหอพักให้ถูกต้อง";
                            }
                            else{
                                //count
                                $data->nocount++;
                                //save to metadata
                                if(!document_server_data_write($data)){
                                    $message = $metadata_error;
                                }
                                else{
                                    //decount
                                    $data->nocount--;
                                    //html results
                                    $message_html = true;
                                    $message = document_header();
                                    $message.= document_title_tax_invoice();
                                    $message.= document_info("เล่มที่    ".$data->bookno."    เลขที่    ".$data->nocount, "ใบกำกับภาษี", $data->name,$data->address,$data->phone,$data->taxid,$owner,$addr,$_GET['date']);
                                    $message.= document_table($_GET['room'], $_GET['water'],$_GET['waterp'],$_GET['nrg'],$_GET['nrgp'],$_GET['price']);
                                    $message.= document_footer_tax_invoice();
                                    //save
                                    $tax_file_location = __DIR__ . "/tax_invoices/tax-invoice-".$data->bookno."-".$data->nocount.".html";
                                    $tax_file = fopen($tax_file_location,"x");
                                    if(!$tax_file){
                                        $message .= "<script>alert('ไม่สามารถสร้างไฟล์ใบกำกับภาษีได้!');</script>";
                                        $message.= document_footer();
                                    }else{
                                        $message.= document_footer();
                                        $message.= document_print();
                                        fwrite($tax_file, $message);
                                        fclose($tax_file);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            //delete
            else if(isset($_GET['del']) && isset($_GET['date'])){
                $query = mysqli_prepare($sql, "DELETE FROM tb_bill WHERE bill_date=?");
                    if(!$query){
                        $message = $query_error;
                    }
                    else{
                        mysqli_stmt_bind_param($query, 's', $_GET['date']);
                        if(mysqli_stmt_execute($query)){
                            $message = "ลบข้อมูลเรียบร้อยแล้ว";
                        }
                        else{
                            $message = "ลบข้อมูลผิดพลาด";
                        }
                    }
            }
            //unknown
            else{
                $message = $unknown_cmd;
            }
        }
        //user
        elseif($_SESSION['mode']===1){
            $next_page = "room_bill.php";
            //upload
            if(isset($_GET['upload']) && isset($_GET['date'])){
                //determine date + random number
                $this_date = date("YmdHis"); for($i=0;$i<10;$i++){ $this_date.=rand(0,9); }
                //determine file
                $this_file = "/bill_receipts/".$this_date;
                //file upload
                $this_upload = file_upload($_FILES["receipt"],$this_file);
                switch($this_upload){
                    //success
                    default:
                        //add data
                        $query = mysqli_prepare($sql,"UPDATE tb_bill SET bill_paydate=?,bill_receipt=? WHERE bill_date=?");
                        if(!$query){
                            //query prepare error
                            $message = "เตรียม Query ผิดพลาด โปรดลองใหม่อีกครั้ง หรือติดต่อผู้ดูแลระบบ";
                        }
                        else{
                            //query
                            mysqli_stmt_bind_param($query, 'sss', $this_date, $this_upload, $_GET['date']);
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
            else{
                $message = $unknown_cmd;
            }
        }
    }
    else{
        $message = "กรุณาล็อกอินก่อนเข้าใช้งาน";
    }
    
    //push to html page
    if($message_html){
        //html
        echo $message;
    }else {
        //JavaScript dialog
        $vecho = "<script>";
        if(!empty($message)){
            $vecho.= "alert('".$message."');";
        }
        $vecho.= "window.open('".$next_page."','_self');</script>";
        echo $vecho;
    }
?>
