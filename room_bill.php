<?php
    require("scr_sql.php");
    $message = "<h1 align=\"center\">- คุณไม่ได้รับอนุญาตให้ใช้งานส่วนนี้! -</h1>";
    session_start();
    if(isset($_SESSION['mode'])){
        if($_SESSION['mode']===1){//authentication
            $query = mysqli_query($sql, "SELECT * FROM tb_bill WHERE bill_room='".$_SESSION['usr']."' ORDER BY bill_date LIMIT 10");
            if(!$query){
                $message = "<h1 align=\"center\">เกิดปัญหาการติดต่อกับเซิร์ฟเวอร์!</h1>".$sql->error;
            }
            else{
                $message = "<hr>";
                $rows = mysqli_num_rows($query);
                for($i=0; $i<$rows; $i++){
                    $data = mysqli_fetch_array($query);
                    $message.= "เวลาที่ออกบิล: ".substr($data['bill_date'],0,4) . "-" . substr($data['bill_date'],4,2) . "-" . substr($data['bill_date'],6,2) . " " . substr($data['bill_date'],8,2) . ":" . substr($data['bill_date'],10,2) . "<br>";
                    $message.= "ค่าน้ำ: ".strval(doubleval($data['bill_water']) * doubleval($data['bill_water_price']))."<br>";
                    $message.= "ค่าไฟ: ".strval(doubleval($data['bill_nrg_price']) * doubleval($data['bill_nrg_price']))."<br>";
                    $message.= "ค่าหอพัก: ".$data['bill_price']."<br>";
                    $invoice_button = "<button type=\"button\" class=\"button-small\" onclick=\"gotolink('".$data['bill_invoice']."')\">พิมพ์ใบแจ้งหนี้</button>";
                    if(empty($data['bill_paydate'])){
                        $message .= "- ยังไม่ชำระ หรือสลิปโอนไม่ถูกต้อง -"."<br>";
                        //form
                        $message .= "<form action=\"scr_bill.php?upload&date=".$data['bill_date']."\" method=\"post\" enctype=\"multipart/form-data\">";
                        $message .=     "<table align=\"center\" style=\"width:80%\">";
                        $message .=         "<tr>";
                        $message .=             "<td>";
                        $message .=                 "<h style=\"height:12px\" align=\"center\">- เลือกไฟล์ที่จะอัปโหลด -</h><br>";
                        $message .=                 "<input type=\"file\" name=\"receipt\">";
                        $message .=             "</td>";
                        $message .=         "</tr><tr>";
                        $message .=             "<td>";
                        $message .=                 "<input type=\"submit\" value=\"กดที่นี่เพื่ออัปโหลดสลิป\">";
                        $message .=             "</td>";
                        $message .=         "</tr><tr>";
                        $message .=             "<td>";
                        $message .=                 $invoice_button;
                        $message .=             "</td>";
                        $message .=         "</tr>";
                        $message .=     "</table>";
                        $message .= "</form>";
                        //end form
                    }
                    else{
                        $message .= "- ชำระแล้ว -"."<br>".$invoice_button;
                    }
                    $message.= "<hr>";
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
<div class="header"><h1>รายการใบแจ้งหนี้</h1></div>
<?php echo $message;?>
<div style="text-align:center;">
    <button class="button button-red" onclick="gotolink('room_ui.php')">ย้อนกลับ</button></p>
</div>
<script>
function gotolink(link){
    window.open(link,'_self');
}
</script>
<!--auth.js-->
<p id="page" type="room"></p>
<script src="scr_auth.js"></script>
<!--auth.js-->
</body>
</html>
