<?php 
    require("scr_sql.php"); //$sql
    $message = "<p style=\"text-align:center;\">คุณไม่ได้รับอนุญาตให้เข้าถึงส่วนนี้</p>";
    //auth
    session_start();
    if(isset($_SESSION['mode'])){
        if($_SESSION['mode'] === 2){
            //query
            $query = mysqli_query($sql, "select * from tb_reserve");
            $rows = mysqli_num_rows($query);
            //result
            $message = "<hr>";
            $i=0;
            if($rows<=0){
                $message = "<p style=\"text-align:center;\">ไม่มีรายการจองในขณะนี้</p>";
            }
            else while($i < $rows){ //0-4 ข้อมูลมี 5
                $result = mysqli_fetch_array($query);
                    $message .= substr($result["date"],0,4) . "-" . substr($result["date"],4,2) . "-" . substr($result["date"],6,2) . " " . substr($result["date"],8,2) . ":" . substr($result["date"],10,2) . "<br>";
                    $message .= "ผู้จอง: " . $result["name"] . "<br>";
                    $message .= "เบอร์โทร: " . $result["phone"] . "<br>";
                    $message .= "ห้องที่จอง: ". $result["room"] . "<br>";
                    $message .= "ชนิดห้อง: ". $result["type"] . "<br>";
                    $message .= "<button class=\"button-small button-red\" onclick=\"remove('".$result['date']."','".$result['name']."','".$result['room']."')\">ลบข้อมูล</button>";
                    $message .= "<button class=\"button-small\" onclick=\"pic('".$result['receipt']."')\">สลิปการโอนเงิน</button>";
                    $message .= "<button class=\"button-small\" onclick=\"confirmRent('".$result['name']."','".$result['phone']."','".$result['room']."','".$result['type']."')\">ให้เข้าพัก</button>";
                $message .= "<hr>";
           $i++;}
           
       }
   }
mysqli_close($sql);?>
<html>
<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
<head>
<title>ระบบจัดการหอพัก</title>
</head>
<body>
<link rel="stylesheet" type="text/css" href="body.css">
<!--navbar-->
<script src="include-html.js"></script>
<script src="navbar.js""></script>
<p include-html="navbar.html"></p>
<script>includeHTML();</script>
<!--navbar-->
<div class="header"><h1>รายการจองทั้งหมด</h1></div>
<?php echo $message;?>
<div style="text-align:center;">
    <button class="button button-red" onclick="back()">ย้อนกลับ</button></p>
</div>
<script>
function back(){
    window.open('admin_ui.html','_self')
}
function pic(id){
    window.open(id, '_self')
}
function confirmRent(name,phone,room,type){
    if(confirm('คุณต้องการยืนยันการเข้าพัก '+room+' ของบุคคลชื่อ '+name+' ใช่หรือไม่?')){
        window.open('room_data.html?owner='+name+'&phone='+phone+'&id='+room+'&type='+type,'_self');
    }
}
function remove(date,name,room){
    if(confirm('คุณต้องการ \"ลบข้อมูล\" การจองห้อง '+room+' ของบุคคลชื่อ '+name+' ใช่หรือไม่?')){
        window.open('scr_reserve.php?remove&date='+date,'_self')
    }
}
</script>
<!--auth.js-->
<p id="page" type="admin"></p>
<script src="scr_auth.js"></script>
<!--auth.js-->
</body>
</html>
