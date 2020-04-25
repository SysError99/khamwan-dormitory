<?php
    require("scr_sql.php");
    $message = "<h1 align=\"center\">- คุณไม่ได้รับอนุญาตให้ใช้งานส่วนนี้! -</h1>";
    $query_error = "<h1 align=\"center\">- Query ผิดพลาด โปรดติดต่อผู้ดูแลระบบ! -</h1>";
    $room_id = "";
    session_start();
    if(isset($_SESSION['mode'])){
        if($_SESSION['mode']===2){
            $room_id = $_SESSION['usr'];
            $query = mysqli_query($sql, "SELECT * FROM tb_report WHERE report_fix<>1 ORDER BY report_date DESC LIMIT 10");
            if(!$query){
                $message = $query_error;
            }
            else{
                //list of data
                $message = "<hr>";
                $rows = mysqli_num_rows($query);
                if($rows>0){
                    
                    for($i=0;$i<$rows;$i++){
                        $data = mysqli_fetch_array($query);
                        $message .= "วันที่รายงาน: ".substr($data['report_date'],0,4) . "-" . substr($data['report_date'],4,2) . "-" . substr($data['report_date'],6,2) . " " . substr($data['report_date'],8,2) . ":" . substr($data['report_date'],10,2) . "<br>";
                        $message .= "ห้องพักที่รายงาน: ".$data['report_room']."<br>";
                        $message .= "รายละเอียดของรายงาน: ".$data['report_detail']."<br>";
                        $message .= "<button class=\"button-small button-blue\" type=\"button\" onclick=\"gotolink('".$data['report_photo']."')\">ดูรูปภาพที่รายงาน</button>";
                        $message .= "<button class=\"button-small button-green\" type=\"button\" onclick=\"gotolink('scr_report.php?fix&date=".$data['report_date']."')\">ปัญหาถูกแก้ไขแล้ว</button>";
                        $message .= "<hr>";
                    }
                }
                else{
                    $message.= "<h1 align=\"center\">- ไม่มีข้อมูล -</h1>";
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
<div class="header"><h1>ปัญหาหอพักทั้งหมด</h1></div>
<?php echo $message;?>
<div style="text-align:center;">
    <button class="button button-red" onclick="gotolink('admin_ui.html')">ย้อนกลับ</button></p>
</div>
<script>
function gotolink(link){
    window.open(link,'_self')
}
</script>
<!--auth.js-->
<p id="page" type="admin"></p>
<script src="scr_auth.js"></script>
<!--auth.js-->
</body>
</html>
