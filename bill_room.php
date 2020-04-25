<?php
    require("scr_sql.php"); //$sql
    $message = "<p style=\"text-align:center;\">คุณไม่ได้รับอนุญาตให้เข้าถึงส่วนนี้</p>";
    //auth
    session_start();
    if(isset($_SESSION['mode'])){
        if($_SESSION['mode'] === 2){
            $message = "<p style=\"text-align:center;\">".$_GET['id']." ไม่มีข้อมูลที่จะแสดง</p>";
            if(isset($_GET['id'])){
                $query = mysqli_prepare($sql,"SELECT * FROM tb_bill WHERE bill_room=?");
                if($query){
                    mysqli_stmt_bind_param($query, 's', $_GET['id']);
                    if(mysqli_stmt_execute($query)){
                        mysqli_stmt_store_result($query);
                        mysqli_stmt_bind_result($query, $b_date, $b_room, $b_nrg, $b_nrgp, $b_water, $b_waterp, $b_price, $b_invoice, $b_paydate, $b_receipt);
                        $b_rows = mysqli_stmt_num_rows($query);
                        if($b_rows > 0){
                            $message = "<h1>บิลของห้อง ".$_GET['id']."</h1><hr>"; //prepare for data
                            for($i=0;$i<$b_rows;$i++){
                                mysqli_stmt_fetch($query);
                                $message.= "เวลาที่ออกบิล: ".substr($b_date,0,4) . "-" . substr($b_date,4,2) . "-" . substr($b_date,6,2) . " " . substr($b_date,8,2) . ":" . substr($b_date,10,2) . "<br>";
                                $message.= "ค่าน้ำ: ".strval(doubleval($b_water) * doubleval($b_waterp))."<br>";
                                $message.= "ค่าไฟ: ".strval(doubleval($b_nrg) * doubleval($b_nrgp))."<br>";
                                $message.= "ค่าหอพัก: ".$b_price."<br>";
                                if(empty($b_paydate)){
                                    $message.= "- ยังไม่ชำระ -"."<br>";
                                }
                                else{
                                    $message.= "วันที่ชำระ: ".substr($b_paydate,0,4) . "-" . substr($b_paydate,4,2) . "-" . substr($b_paydate,6,2) . " " . substr($b_paydate,8,2) . ":" . substr($b_paydate,10,2) ."<br>";
                                    $message.= "<button class=\"button-small \" onclick=\"pic('".$b_receipt."')\">ดูสลิปการโอน</button>";
                                    $message.= "<button class=\"button-small button-red\" onclick=\"invalid('".$b_date."')\">สลิปไม่ถูกต้อง</button>";
                                    $message.= "<button class=\"button-small button-green\" onclick=\"tax('".$b_date."','".$_GET['id']."','".$b_nrg."','".$b_nrgp."','".$b_water."','".$b_waterp."','".$b_price."','".$b_paydate."')\">ออกใบกำกับภาษีใหม่</button>";
                                }
                                $message.= "<button class=\"button-small button-green\" onclick=\"invoice('".$b_invoice."')\">พิมพ์ใบแจ้งหนี้</button>";
                                $message.= "<button class=\"button-small button-red\" onclick=\"del('".$b_date."')\">ลบออกจากระบบ</button>";
                                $message.= "<hr>";
                            }
                        }
                    }
                }
            }
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
<script>includeHTML()</script>
<!--navbar-->
<div class="header"><h1>จัดการบิล</h1></div>
<div class="container">
    <p align=center><button class="button-small button-green" onclick="add()"><h1>เพิ่มบิลใหม่</h1></button></p>
</div>
<?php echo $message;?>
<div style="text-align:center;">
    <button class="button button-red" onclick="back()">ย้อนกลับ</button></p>
</div>
<script>
function back(){
    window.open('bill_list.php','_self')
}
function add(){
    window.open('bill_add.html?id='+<?php echo "'".$_GET['id']."'"; ?>,'_self')
}
function pic(location){
    window.open(location,'_self')
}
function invalid(date){
    if(confirm('สลิปโอนของบิล '+date+' ไม่ถูกต้องใช่หรือไม่?')){
        window.open('scr_bill.php?invalid&date='+date,'_self')
    }
}
function invoice(url){
    window.open(url,'_self')
}
function tax(date,room,nrg,nrgp,water,waterp,price,paydate){
    window.open('scr_bill.php?tax&date='+date+'&room='+room+'&nrg='+nrg+'&nrgp='+nrgp+'&water='+water+'&waterp='+waterp+'&price='+price+'&paydate='+paydate, '_self')
}
function del(date){
    if(confirm('คุณต้องการลบบิล '+date+' ออกไปจากระบบใช่หรือไม่?')){
        window.open('scr_bill.php?del&date='+date,'_self');
    }
}
</script>
<!--auth.js-->
<p id="page" type="admin"></p>
<script src="scr_auth.js"></script>
<!--auth.js-->
</body>
</html>
