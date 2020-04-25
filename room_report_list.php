<?php
    require("scr_sql.php");
    $message = "<h1 align=\"center\">- คุณไม่ได้รับอนุญาตให้ใช้งานส่วนนี้! -</h1>";
    $query_error = "<h1 align=\"center\">- Query ผิดพลาด โปรดติดต่อผู้ดูแลระบบ! -</h1>";
    $room_id = "";
    session_start();
    if(isset($_SESSION['mode'])){
        if($_SESSION['mode']===1){
            $room_id = $_SESSION['usr'];
            $query = mysqli_prepare($sql, "SELECT * FROM tb_report WHERE report_room=?");
            if(!$query){
                $message = $query_error;
            }
            else{
                mysqli_stmt_bind_param($query, 's', $_SESSION['usr']);
                if(mysqli_stmt_execute($query)){
                    //list of data
                    $message = "<hr>";
                    mysqli_stmt_store_result($query);
                    mysqli_stmt_bind_result($query, $date, $room, $detail, $photo, $fix);
                    $rows = mysqli_stmt_num_rows($query);
                    if($rows>0){
                        for($i=0;$i<$rows;$i++){
                            mysqli_stmt_fetch($query);
                            $message .= "วันที่รายงาน: ".substr($date,0,4) . "-" . substr($date,4,2) . "-" . substr($date,6,2) . " " . substr($date,8,2) . ":" . substr($date,10,2) . "<br>";
                            $message .= "รายละเอียดของรายงาน: ".$detail."<br>";
                            if($fix===1){
                                $message .= "<p style=\"color:red\">- เจ้าของหอพักแจ้งว่าแก้ไขแล้ว -</p><br>";
                            }
                            $message .= "<button class=\"button-small button-blue\" type=\"button\" onclick=\"gotolink('".$photo."')\">ดูรูปภาพที่รายงาน</button>";
                            $message .= "<button class=\"button-small button-green\" type=\"button button-red\" onclick=\"gotolink('scr_report.php?fix&date=".$date."')\">ลบออกจากระบบ</button>";
                            $message .= "<hr>";
                        }
                    }
                    else{
                        $message.= "<h1 align=\"center\">- ไม่มีข้อมูล -</h1>";
                    }
                }
            }
        }
    }
?>
<html>
<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
<head>
<title>ระบบจัดการห้องพัก</title>
</head>
<body>
<link rel="stylesheet" type="text/css" href="body.css">
<!--navbar-->
<script src="include-html.js"></script>
<script src="navbar.js""></script>
<p include-html="navbar.html"></p>
<script>includeHTML();</script>
<!--navbar-->
<div class="header"><h1>ปัญหาหอพัก</h1></div>
<div class="container">
    <p align=center><button class="button-small button-green" <?php echo "onclick=\"gotolink('room_report.html?room=".$room_id."')\""?>><h1>เพิ่มรายงานใหม่</h1></button></p>
</div>
<?php echo $message;?>
<div style="text-align:center;">
    <button class="button button-red" onclick="gotolink('room_ui.php')">ย้อนกลับ</button></p>
</div>
<script>
function gotolink(link){
    window.open(link,'_self')
}
</script>
<!--auth.js-->
<p id="page" type="room"></p>
<script src="scr_auth.js"></script>
<!--auth.js-->
</body>
</html>
