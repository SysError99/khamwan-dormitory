<?php 
    require("scr_sql.php"); //$sql
    $message = "<p style=\"text-align:center;\">คุณไม่ได้รับอนุญาตให้เข้าถึงส่วนนี้</p>";
    //auth
    session_start();
    if(isset($_SESSION['mode'])){
        if($_SESSION['mode'] === 2){
            $query = mysqli_query($sql, "SELECT * FROM tb_room");
            if($query){
                $rows = mysqli_num_rows($query);
                if($rows>0){
                    //data list
                    $message = "<table style=\"width:100%\">";
                    $message.= "<tr><th><span class=\"checkmark\"></span>รายการห้องพักทั้งหมด</th></tr><tr><td>";
                    for($i=0;$i<$rows;$i++){
                        $data = mysqli_fetch_array($query);
                        //$message.= "<tr><td>";
                        //button
                        $message.= "<button class=\"button-small button-blue\" onclick=\"edit('".$data['room_id']."','".$data['room_type']."','".$data['room_owner']."','".$data['room_owner_addr']."','".$data['room_owner_phone']."')\"><h1><i class=\"fa fa-fw fa-home\"></i> ".$data['room_id']."</h1></button>";
                        //end-button
                        //$message.= "</td></tr>";
                    }
                    $message.= "</td></tr></table>";
                }
                else{
                    $message = "<p style=\"text-align:center;\">ไม่มีข้อมูลห้องพักในระบบ</p>";
                }
            }
            else{
                $messsage = "<p style=\"text-align:center;\">ติดต่อกับฐานข้อมูลผิดพลาด</p>";
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
<div class="header"><h1>รายการห้องพักทั้งหมด</h1></div>
<div class="container">
    <p align=center><button class="button-small button-green" onclick="add()"><h1>เพิ่มห้องใหม่</h1></button></p>
</div>
<?php echo $message;?>
<div style="text-align:center;">
    <button class="button button-red" onclick="back()">ย้อนกลับ</button></p>
</div>
<script>
function add(){
    window.open('room_data.html','_self');
}
function back(){
    window.open('admin_ui.html','_self')
}
function edit(id,type,owner,addr,phone){
    window.open('room_data.html?id='+id+'&type='+type+'&owner='+owner+'&addr='+addr+'&phone='+phone,'_self');
}
</script>
<!--auth.js-->
<p id="page" type="admin"></p>
<script src="scr_auth.js"></script>
<!--auth.js-->
</body>
</html>
