<?php
    require("scr_sql.php");
    session_start();
    $header_message = "คุณไม่มีสิทธิ์ใช้งานส่วนนี้!";
    if(isset($_SESSION['mode'])){
        if($_SESSION['mode']===1){
            $header_message = "ห้องพัก ".$_SESSION['usr'];
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
<img src="header_renter.png" style="width:100%">
<h1 align="center"><?php echo $header_message;?>
<div style="text-align:center;">
    <h>- เมนูทำงาน -</h>
    <p><button class="button" onclick="gotolink('room_bill.php')"> การแจ้งหนี้ </button>
    <p><button class="button" onclick="gotolink('room_report_list.php')"> รายงานปัญหาภายในหอพัก </button>
    <h>- เมนูล็อกอิน -</h>
    <p><button class="button button-red" onclick="gotolink('scr_auth.php?logout')">ออกจากระบบ</button></p>
</div>
<!--auth.js-->
<p id="page" type="room"></p>
<script src="scr_auth.js"></script>
<!--auth.js-->
<script>
function gotolink(link){
    window.open(link,'_self');
}
</script>
</body>
</html>
